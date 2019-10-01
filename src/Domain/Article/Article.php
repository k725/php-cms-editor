<?php
declare(strict_types=1);

namespace App\Domain\Article;

use JsonSerializable;

class Article implements JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string|null
     */
    private $updatedAt;

    /**
     * @var array
     */
    private $parts;

    /**
     * @var array
     */
    private $refs;

    /**
     * Article constructor.
     * @param int $id
     * @param string $title
     * @param string $description
     * @param string $createdAt
     * @param string|null $updatedAt
     * @param array $parts
     * @param array $refs
     */
    public function __construct(
        int $id,
        string $title,
        string $description,
        string $createdAt,
        ?string $updatedAt,
        array $parts,
        array $refs
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->parts = $parts;
        $this->refs = $refs;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function getParts(): array
    {
        return $this->parts;
    }

    public function getRefs(): array
    {
        return $this->refs;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $refArticles = [];
        foreach ($this->refs as $r) {
            $refArticles[(string)$r['id']] = $r;
        }

        $parts = $this->parts;
        foreach ($parts as $i => $p) {
            if ($p['name'] !== 'reference') {
                continue;
            }
            $parts[$i]['data'] = $refArticles[(string)$p['data']];
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'parts' => $parts,
        ];
    }
}