<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Events;

use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Schema\SchemaGraph;

final class SchemaGraphResolving
{
    public function __construct(
        public SchemaGraph $graph,
        public readonly ContentContext $context,
        public readonly SeoDocument $document,
    ) {}
}
