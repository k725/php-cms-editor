<?php
declare(strict_types=1);

namespace App\Domain\Article;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ArticleNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The user you requested does not exist.';
}