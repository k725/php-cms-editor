<?php
declare(strict_types=1);

namespace App\Application\Actions\Article\API;

use App\Application\Actions\Article\ArticleAction;
use Psr\Http\Message\ResponseInterface as Response;

class ViewArticlesListAPIAction extends ArticleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $articles = $this->articleRepository->findAll();
        return $this->respondWithData($articles);
    }
}