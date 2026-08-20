<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Data;

final readonly class SeoDocument
{
    public ?string $title;

    public ?string $description;

    /**
     * @param  array<string, mixed>  $openGraph
     * @param  array<string, mixed>  $twitter
     * @param  array<string, mixed>|list<array<string, mixed>>  $schema
     */
    public function __construct(
        ?string $title = null,
        ?string $description = null,
        public ?CanonicalUrl $canonical = null,
        public ?RobotsDirective $robots = null,
        public array $openGraph = [],
        public array $twitter = [],
        public array $schema = [],
    ) {
        $this->title = self::normalize($title);
        $this->description = self::normalize($description);
    }

    public static function empty(): self
    {
        return new self;
    }

    public function withTitle(?string $title): self
    {
        return new self($title, $this->description, $this->canonical, $this->robots, $this->openGraph, $this->twitter, $this->schema);
    }

    public function withDescription(?string $description): self
    {
        return new self($this->title, $description, $this->canonical, $this->robots, $this->openGraph, $this->twitter, $this->schema);
    }

    public function withCanonical(?CanonicalUrl $canonical): self
    {
        return new self($this->title, $this->description, $canonical, $this->robots, $this->openGraph, $this->twitter, $this->schema);
    }

    public function withRobots(?RobotsDirective $robots): self
    {
        return new self($this->title, $this->description, $this->canonical, $robots, $this->openGraph, $this->twitter, $this->schema);
    }

    /** @param array<string, mixed> $openGraph */
    public function withOpenGraph(array $openGraph): self
    {
        return new self($this->title, $this->description, $this->canonical, $this->robots, $openGraph, $this->twitter, $this->schema);
    }

    /** @param array<string, mixed> $twitter */
    public function withTwitter(array $twitter): self
    {
        return new self($this->title, $this->description, $this->canonical, $this->robots, $this->openGraph, $twitter, $this->schema);
    }

    /** @param array<string, mixed>|list<array<string, mixed>> $schema */
    public function withSchema(array $schema): self
    {
        return new self($this->title, $this->description, $this->canonical, $this->robots, $this->openGraph, $this->twitter, $schema);
    }

    private static function normalize(?string $value): ?string
    {
        $value = $value === null ? null : trim($value);

        return $value === '' ? null : $value;
    }
}
