<?php
declare(strict_types=1);

namespace App\Domain\Article;

interface ArticleRepository
{
    /**
     * @return Article[]
     */
    public function findAll(): array;

    public function findArticleSummaryOfId(int $id): array;

    public function findArticleDetailOfId(int $id): Article;

    public function addArticleParts(int $articleId, string $articleType, string $data);

    public function deleteArticleParts(int $articleId, int $partsId);

    public function updateArticleSummary(int $articleId, string $title, string $description);
}