<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Contracts;

use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Schema\SchemaNode;

interface SchemaProvider
{
    public function supports(ContentContext $context): bool;

    /** @return iterable<SchemaNode> */
    public function provide(ContentContext $context, SeoDocument $document): iterable;
}
