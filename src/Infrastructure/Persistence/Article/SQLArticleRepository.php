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

    public function findAll(): array
    {
        try {
            $stmt = $this->pdo->query('
                SELECT
                    id, title, created_at
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
            $this->logger->error('Failed fetch article.', [
                'exception' => $e,
            ]);
        }
        return [];
    }

    public function findArticleSummaryOfId(int $id): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT
                    id, title, created_at
                FROM
                    article
                WHERE
                    id = :id;
            ');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll() ?? [];
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch article.', [
                'exception' => $e,
            ]);
        }
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function findArticleDetailOfId(int $id): Article
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
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $r = $stmt->fetch();
            if ($r == false) {
                throw new ArticleNotFoundException();
            }
            $p = $this->findArticlePartsOfId($id);
            $ref = $this->findReferenceArticles($id);
            return new Article((int)$r['id'], $r['title'], $r['description'], $r['created_at'], $r['updated_at'], $p, $ref);
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch article.', [
                'exception' => $e,
            ]);
        }
        throw new ArticleNotFoundException();
    }

    private function findArticlePartsOfId(int $id): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT
                    article_parts.id AS parts_id,
                    article_parts.article_order AS article_order,
                    article_parts.data AS data,
                    parts_type.name AS name
                FROM
                    article_parts
                LEFT JOIN parts_type on article_parts.type = parts_type.id
                WHERE
                    article_parts.article_id = :id
                ORDER BY
                    article_parts.article_order ASC,
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
            return $stmt->fetchAll() ?? [];
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch articles.', [
                'exception' => $e,
            ]);
        }
        return [];
    }

    public function updateArticlePartsOrder(int $articleId, int $partsId, int $order)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO
                    article_parts (id, article_id, article_order, updated_at)
                VALUES
                    (:id, :articleId, :articleOrder, CURRENT_TIMESTAMP)
                ON DUPLICATE KEY UPDATE
                    article_order = VALUES(article_order),
                    updated_at = VALUES(updated_at);
            ");
            $stmt->bindValue(':id', $partsId, PDO::PARAM_INT);
            $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
            $stmt->bindValue(':articleOrder', $order, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch articles.', [
                'exception' => $e,
            ]);
        }
    }

    public function addArticleParts(int $articleId, string $articleType, string $data)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO
                    article_parts (id, article_id, article_order, type, data, created_at, updated_at)
                VALUES
                    (
                        NULL,
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
                                    article_parts.article_id = 1
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
                    )
                ON DUPLICATE KEY UPDATE
                    article_order = VALUES(article_order),
                    updated_at = VALUES(updated_at);
            ");
            $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
            $stmt->bindValue(':partsType', $articleType, PDO::PARAM_STR);
            $stmt->bindValue(':partsData', $data, PDO::PARAM_STR);
            $stmt->execute();
            return (int)$this->pdo->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch articles.', [
                'exception' => $e,
            ]);
        }
        return -1;
    }

    public function updateArticleSummary(int $articleId, string $title, string $description)
    {
        try {
            // @todo ↓のクエリだと開発環境が zend_mm_heap corrupted で落ちる…
//            $stmt = $this->pdo->prepare('
//                UPDATE
//                    article
//                SET
//                    title = :articleTitle,
//                    description = :articleDesc,
//                    updated_at = CURRENT_TIMESTAMP
//                WHERE
//                    id = :articleId;
//            ');
            $stmt = $this->pdo->prepare('
                INSERT INTO
                    article (id, title, description, updated_at)
                VALUES
                    (:articleId, :articleTitle, :articleDesc, CURRENT_TIMESTAMP)
                ON DUPLICATE KEY UPDATE
                    id = VALUES(id),
                    title = VALUES(title),
                    description = VALUES(description),
                    updated_at = VALUES(updated_at);
            ');
            $stmt->bindValue(':articleTitle', $title, PDO::PARAM_STR);
            $stmt->bindValue(':articleDesc', $description, PDO::PARAM_STR);
            $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch articles.', [
                'exception' => $e,
            ]);
        }
    }

    public function deleteArticleParts(int $articleId, int $partsId)
    {
        try {
            $stmt = $this->pdo->prepare('
                DELETE
                FROM
                    article_parts
                WHERE
                    id = :partsId AND
                    article_id = :articleId;
            ');

            $stmt->bindValue(':partsId', $partsId, PDO::PARAM_INT);
            $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->logger->error('Failed fetch articles.', [
                'exception' => $e,
            ]);
        }
    }
}