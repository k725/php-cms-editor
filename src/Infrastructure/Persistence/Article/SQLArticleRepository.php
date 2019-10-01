<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Article;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleNotFoundException;
use App\Domain\Article\ArticleRepository;
use PDO;
use PDOException;
use Psr\Log\LoggerInterface;

class SQLArticleRepository implements ArticleRepository
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SQLArticleRepository constructor.
     * @param PDO $pdo
     * @param LoggerInterface $logger
     */
    public function __construct(PDO $pdo, LoggerInterface $logger)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->pdo->query('
                SELECT
                    id, title, created_at as createdAt, updated_at as updatedAt
                FROM
                    article;
            ');
            $stmt->execute();
            $result = $stmt->fetchAll();
            if ($result === false) {
                return [];
            }
            foreach ($result as $i => $r) {
                $result[$i]['id'] = (int)$r['id'];
            }
            return $result;
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch all articles.', [
                'exception' => $e,
            ]);
        }
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function findArticleDetailOfId(int $articleId): Article
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT
                    id, title, description, created_at, updated_at
                FROM
                    article
                WHERE
                    id = :id;
            ');
            $stmt->bindValue(':id', $articleId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result === false) {
                throw new ArticleNotFoundException();
            }

            $parts = $this->findArticlePartsOfId($articleId);
            $ref = $this->findReferenceArticles($articleId);
            return new Article(
                (int)$result['id'],
                $result['title'],
                $result['description'],
                $result['created_at'],
                $result['updated_at'],
                $parts,
                $ref
            );
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch article.', [
                'exception' => $e,
                'article_id' => $articleId,
            ]);
        }
        throw new ArticleNotFoundException();
    }

    /**
     * {@inheritDoc}
     */
    public function updateArticlePartsOrder(int $articleId, int $old, int $new): bool
    {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("
                UPDATE article_parts
                SET
                    article_order =
                        CASE
                            WHEN article_order = :old THEN :newN
                            WHEN article_order = :new THEN :oldN
                        END
                WHERE
                    article_order IN (:old, :new) AND
                    article_id = :articleId;
            ");
            $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
            $stmt->bindValue(':old', $old, PDO::PARAM_INT);
            $stmt->bindValue(':oldN', -$old, PDO::PARAM_INT);
            $stmt->bindValue(':new', $new, PDO::PARAM_INT);
            $stmt->bindValue(':newN', -$new, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->pdo->prepare("
                UPDATE article_parts
                SET
                    article_order = - article_order,
                    updated_at = CURRENT_TIMESTAMP
                WHERE
                    article_order IN (:oldN, :newN) AND
                    article_id = :articleId;
            ");
            $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
            $stmt->bindValue(':oldN', -$old, PDO::PARAM_INT);
            $stmt->bindValue(':newN', -$new, PDO::PARAM_INT);
            $stmt->execute();

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->logger->error('Failed fetch articles.', [
                'exception' => $e,
            ]);
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function addArticleParts(int $articleId, string $articleType, string $data): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO
                    article_parts (article_id, article_order, type, data, created_at, updated_at)
                VALUES
                    (
                        :articleId,
                        (
                            SELECT
                                max_value
                            FROM (
                                SELECT
                                    MAX(article_parts.article_order) + 1 AS max_value
                                FROM
                                    article_parts
                                WHERE
                                    article_parts.article_id = :articleId
                            ) AS TMP
                        ),
                        (
                            SELECT
                                parts_type.id
                            FROM
                                parts_type
                            WHERE
                                parts_type.name = :partsType
                        ),
                        :partsData,
                        CURRENT_TIMESTAMP,
                        CURRENT_TIMESTAMP
                    );
            ");
            $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
            $stmt->bindValue(':partsType', $articleType, PDO::PARAM_STR);
            $stmt->bindValue(':partsData', $data, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch articles.', [
                'exception' => $e,
            ]);
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function updateArticleSummary(int $articleId, string $title, string $description): bool
    {
        try {
            $stmt = $this->pdo->prepare('
                UPDATE
                    article
                SET
                    title = :articleTitle,
                    description = :articleDesc,
                    updated_at = CURRENT_TIMESTAMP
                WHERE
                    id = :articleId;
            ');
            $stmt->bindValue(':articleTitle', $title, PDO::PARAM_STR);
            $stmt->bindValue(':articleDesc', $description, PDO::PARAM_STR);
            $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch articles.', [
                'exception' => $e,
            ]);
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteArticleParts(int $articleId, int $articleOrder): bool
    {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare('
                DELETE
                FROM
                    article_parts
                WHERE
                    article_order = :articleOrder AND
                    article_id = :articleId;
            ');
            $stmt->bindValue(':articleOrder', $articleOrder, PDO::PARAM_INT);
            $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->pdo->prepare('
                UPDATE
                    article_parts
                SET
                    article_order = article_order - 1,
                    updated_at = CURRENT_TIMESTAMP
                WHERE
                    article_order > :articleOrder;
            ');
            $stmt->bindValue(':articleOrder', $articleOrder, PDO::PARAM_INT);
            $stmt->execute();

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {

            $this->logger->error('Failed fetch articles.', [
                'exception' => $e,
            ]);
            $this->pdo->rollBack();
        }
        return false;
    }

    /**
     * @param int $id
     * @return array
     */
    private function findArticlePartsOfId(int $id): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT
                    article_parts.data AS data,
                    parts_type.name AS name
                FROM
                    article_parts
                LEFT JOIN parts_type on article_parts.type = parts_type.id
                WHERE
                    article_parts.article_id = :id
                ORDER BY
                    article_parts.article_order,
                    article_parts.updated_at DESC;
            ');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll() ?? [];
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch article parts.', [
                'exception' => $e,
            ]);
        }
        return [];
    }

    /**
     * @param int $id
     * @return array
     */
    private function findReferenceArticles(int $id): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    id, title, description
                FROM
                    article
                WHERE
                    article.id IN(
                        SELECT
                            article_parts.data
                        FROM
                            article_parts
                        WHERE
                            article_parts.article_id = :id AND
                            article_parts.type = (
                                SELECT
                                    parts_type.id
                                FROM
                                    parts_type
                                WHERE
                                    parts_type.name = 'reference'
                            )
                    );
            ");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if ($result === false) {
                return [];
            }
            foreach ($result as $i => $r) {
                $result[$i]['id'] = (int)$r['id'];
            }
            return $result;
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch references.', [
                'exception' => $e,
            ]);
        }
        return [];
    }
}