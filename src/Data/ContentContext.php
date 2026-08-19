<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Data;

use DateTimeInterface;
use InvalidArgumentException;

final readonly class ContentContext
{
    public string $type;

    public string $identifier;

    public ?string $url;

    public ?string $title;

    public ?string $body;

    /**
     * @param  array<string, mixed>  $author
     * @param  list<mixed>  $terms
     * @param  array<string, mixed>  $source
     */
    public function __construct(
        string $type,
        string|int $identifier,
        ?string $url = null,
        ?string $title = null,
        ?string $body = null,
        public ?DateTimeInterface $publishedAt = null,
        public ?DateTimeInterface $updatedAt = null,
        public array $author = [],
        public array $terms = [],
        public array $source = [],
    ) {
        $type = trim($type);
        $identifier = trim((string) $identifier);

        if ($type === '' || $identifier === '') {
            throw new InvalidArgumentException('Content type and identifier are required.');
        }

        $this->type = $type;
        $this->identifier = $identifier;
        $this->url = self::normalize($url);
        $this->title = self::normalize($title);
        $this->body = self::normalize($body);
    }

    private static function normalize(?string $value): ?string
    {
        $value = $value === null ? null : trim($value);

        return $value === '' ? null : $value;
    }
}
