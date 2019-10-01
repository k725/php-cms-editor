<?php
declare(strict_types=1);

use App\Domain\Article\ArticleRepository;
use App\Infrastructure\Persistence\Article\SQLArticleRepository;
use DI\ContainerBuilder;
use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        ArticleRepository::class => autowire(SQLArticleRepository::class),
    ]);
};
