<?php
declare(strict_types=1);

namespace App\Application\Actions\Article\API;

use App\Application\Actions\Article\ArticleAction;
use Psr\Http\Message\ResponseInterface as Response;

class ViewArticleAPIAction extends ArticleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $articleId = (int)$this->args['id'];
        $article = $this->articleRepository->findArticleDetailOfId($articleId);

        return $this->respondWithData($article);
    }
}