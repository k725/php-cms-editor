<?php
declare(strict_types=1);

namespace App\Application\Actions\Article;

use App\Application\Actions\Action;
use App\Domain\Article\ArticleRepository;
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
     * ArticleAction constructor.
     * @param LoggerInterface $logger
     * @param ArticleRepository $articleRepository
     * @param Twig $twig
     */
    public function __construct(
        LoggerInterface $logger,
        ArticleRepository $articleRepository,
        Twig $twig
    )
    {
        parent::__construct($logger);
        $this->articleRepository = $articleRepository;
        $this->twig = $twig;
    }
}
