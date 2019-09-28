<?php
declare(strict_types=1);

namespace App\Application\Actions\Article;

use App\Application\Actions\Action;
use App\Domain\Article\ArticleRepository;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

abstract class ArticleAction extends Action
{
    /**
     * @var ArticleRepository
     */
    protected $articleRepository;

    /**
     * @var Twig
     */
    protected $twig;

    /**
     * @param LoggerInterface $logger
     * @param ArticleRepository $articleRepository
     */
    public function __construct(LoggerInterface $logger, ArticleRepository $articleRepository, Twig $twig)
    {
        parent::__construct($logger);
        $this->articleRepository = $articleRepository;
        $this->twig = $twig;
    }
}
