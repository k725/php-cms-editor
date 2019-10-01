<?php
declare(strict_types=1);

namespace App\Application\Actions\Article\API;

use App\Application\Actions\Article\ArticleAction;
use Psr\Http\Message\ResponseInterface as Response;

class EditArticleAPIAction extends ArticleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $articleId = (int)$this->args['id'];

        $data = $this->getFormData();
        switch ($data->mode)
        {
            case 'add':
                $r = $this->articleRepository->addArticleParts($articleId, $data->type, (string)$data->data);
                return $this->respondWithData($r);

            case 'update':
                $r = $this->articleRepository->updateArticlePartsOrder($articleId, (int)$data->oldOrder, (int)$data->newOrder);
                return $this->respondWithData($r);

            case 'delete':
                $r = $this->articleRepository->deleteArticleParts($articleId, $data->order);
                return $this->respondWithData($r);

            case 'summary':
                $r = $this->articleRepository->updateArticleSummary($articleId, $data->title, $data->description);
                return $this->respondWithData($r);
        }
        return $this->respondWithData([]);
    }
}