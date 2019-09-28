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
}