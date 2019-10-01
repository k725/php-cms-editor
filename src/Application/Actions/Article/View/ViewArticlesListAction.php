<?php
declare(strict_types=1);

namespace App\Application\Actions\Article\View;

use App\Application\Actions\Article\ArticleAction;
use Psr\Http\Message\ResponseInterface as Response;

class ViewArticlesListAction extends ArticleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $articles = $this->articleRepository->findAll();

        return $this->twig->render($this->response, 'list.twig', [
            'articles' => $articles,
        ]);
    }
}