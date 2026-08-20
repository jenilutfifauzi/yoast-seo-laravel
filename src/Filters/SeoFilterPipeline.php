<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Filters;

use Jenlut\YoastSeoLaravel\Contracts\SeoExtension;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Schema\SchemaGraph;
use Throwable;

final readonly class SeoFilterPipeline
{
    /** @param list<SeoExtension> $extensions */
    public function __construct(private array $extensions = []) {}

    public function apply(SchemaGraph $graph, ContentContext $context, SeoDocument $document): SchemaGraph
    {
        foreach ($this->extensions as $extension) {
            try {
                $staged = $extension->extend(clone $graph, $context, $document);
                $graph = $staged;
            } catch (Throwable) {
                // ponytail: optional extensions fail closed; schema output remains available.
                continue;
            }
        }

        return $graph;
    }
}

// ponytail: registration order is the only ordering contract; callers own priority if needed.
