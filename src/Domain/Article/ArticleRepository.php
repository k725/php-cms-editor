<?php
declare(strict_types=1);

namespace App\Domain\Article;

interface ArticleRepository
{
    /**
     * @return Article[]
     */
    public function findAll(): array;

    /**
     * @param int $articleId
     * @return Article
     * @throws ArticleNotFoundException
     */
    public function findArticleDetailOfId(int $articleId): Article;

    /**
     * @param int $articleId
     * @param string $articleType
     * @param string $data
     * @return bool
     */
    public function addArticleParts(int $articleId, string $articleType, string $data): bool;

    /**
     * @param int $articleId
     * @param int $articleOrder
     * @return bool
     */
    public function deleteArticleParts(int $articleId, int $articleOrder): bool;

    /**
     * @param int $articleId
     * @param string $title
     * @param string $description
     * @return bool
     */
    public function updateArticleSummary(int $articleId, string $title, string $description): bool;

    /**
     * @param int $articleId
     * @param int $old
     * @param int $new
     * @return bool
     */
    public function updateArticlePartsOrder(int $articleId, int $old, int $new): bool;
}