<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Data;

final readonly class IndexableData
{
    /**
     * @param  array<string, mixed>  $openGraph
     * @param  array<string, mixed>  $twitter
     * @param  array<string, mixed>|list<array<string, mixed>>  $schema
     */
    public function __construct(
        public string $objectType,
        public string $objectId,
        public ?string $permalink,
        public string $permalinkHash,
        public ?string $title = null,
        public ?string $description = null,
        public ?CanonicalUrl $canonical = null,
        public ?RobotsDirective $robots = null,
        public bool $public = true,
        public array $openGraph = [],
        public array $twitter = [],
        public array $schema = [],
    ) {}
}
