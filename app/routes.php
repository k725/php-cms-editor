<?php
declare(strict_types=1);

use App\Application\Actions\Article\{
    API\ViewArticlesListAPIAction,
    API\ViewArticleAPIAction,
    API\EditArticleAPIAction,
    View\ViewArticlesListAction,
    View\ViewArticleAction,
    View\ViewEditArticleAction
};
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->get('/articles', ViewArticlesListAction::class);
    $app->group('/articles/{id}', function (Group $group) {
        $group->get('', ViewArticleAction::class);
        $group->get('/edit', ViewEditArticleAction::class);
    });

    $app->group('/api/articles', function (Group $group) {
        $group->get('', ViewArticlesListAPIAction::class);
        $group->get('/{id}', ViewArticleAPIAction::class);
        $group->post('/{id}', EditArticleAPIAction::class);
    });
};
