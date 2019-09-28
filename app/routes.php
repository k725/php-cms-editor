<?php
declare(strict_types=1);

use App\Application\Actions\Article\{ViewArticleAPIAction,
    ViewArticlesListAction,
    EditArticleAction,
    ViewArticleAction,
    ViewEditArticleAction};
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->get('/articles', ViewArticlesListAction::class);
    $app->group('/articles/{id}', function (Group $group) {
        $group->get('', ViewArticleAction::class);
        $group->get('/edit', ViewEditArticleAction::class);
    });

    $app->group('/api/articles/{id}', function (Group $group) {
        $group->get('', ViewArticleAPIAction::class);
        $group->post('', EditArticleAction::class);
    });
};
