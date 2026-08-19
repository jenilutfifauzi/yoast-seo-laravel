<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Indexables;

use Jenlut\YoastSeoLaravel\Contracts\IndexableBuilder;
use Jenlut\YoastSeoLaravel\Data\CanonicalUrl;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\IndexableData;

final class DefaultIndexableBuilder implements IndexableBuilder
{
    public function build(ContentContext $context): IndexableData
    {
        $permalink = $context->url;

        return new IndexableData(
            objectType: $context->type,
            objectId: $context->identifier,
            permalink: $permalink,
            permalinkHash: hash('sha256', $permalink ?? $context->type.':'.$context->identifier),
            title: $context->title,
            description: $context->body,
            canonical: $permalink === null ? null : CanonicalUrl::fromString($permalink),
            public: true,
        );
    }
}
