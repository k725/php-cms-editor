<?php
declare(strict_types=1);

namespace App\Application\Actions\Article;

use Psr\Http\Message\ResponseInterface as Response;

class EditArticleAction extends ArticleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $articleId = (int) $this->args['id'];

        $data = $this->getFormData();
        if (!isset($data->title, $data->description, $data->parts)) {
            return $this->response->withStatus(500);
        }


        $users = $this->articleRepository->findArticleDetailOfId($articleId);
        return $this->respondWithData($users);
    }
}