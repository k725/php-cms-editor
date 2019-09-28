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
        switch ($data->mode)
        {
            case "add":
                $r = $this->articleRepository->addArticleParts($articleId, $data->type, $data->data);
                return $this->respondWithData([
                    'id' => $r,
                ]);

            case "update":
                // order
                break;

            case "delete":
                $this->articleRepository->deleteArticleParts($articleId, $data->id);
                return $this->respondWithData();

            case "summary":
                $this->articleRepository->updateArticleSummary($articleId, $data->title, $data->description);
                return $this->respondWithData();
        }

        $users = $this->articleRepository->findArticleDetailOfId($articleId);
        return $this->respondWithData($users);
    }
}