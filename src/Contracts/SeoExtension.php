<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Contracts;

use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Schema\SchemaGraph;

interface SeoExtension
{
    public function extend(SchemaGraph $graph, ContentContext $context, SeoDocument $document): SchemaGraph;
}

// ponytail: one extension contract covers graph filters without a second event-specific abstraction.
