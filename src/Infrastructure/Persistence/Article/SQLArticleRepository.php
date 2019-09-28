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
            return $stmt->fetchAll() ?? [];
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
            return new Article((int)$r['id'], $r['title'], $r['description'], $r['created_at'], $r['updated_at'], $p);
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
                    article_parts.article_order AS article_order,
                    article_parts.data AS data,
                    parts_type.name AS name
                FROM
                    article_parts
                LEFT JOIN parts_type on article_parts.type = parts_type.id
                WHERE
                    article_parts.article_id = :id;
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

    private function findReferenceArticles(int $id): array {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    id, title, created_at
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
}