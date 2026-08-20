<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Schema\Providers;

use Jenlut\YoastSeoLaravel\Contracts\SchemaProvider;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Schema\SchemaNode;

final class DefaultSchemaProvider implements SchemaProvider
{
    public function supports(ContentContext $context): bool
    {
        return $context->url !== null;
    }

    public function provide(ContentContext $context, SeoDocument $document): iterable
    {
        $base = $context->url;

        yield new SchemaNode([
            '@type' => 'WebPage',
            '@id' => $base.'#webpage',
            'url' => $base,
            'name' => $document->title ?? $context->title,
            'description' => $document->description ?? $context->body,
        ]);
        yield new SchemaNode(['@type' => 'WebSite', '@id' => $base.'#website', 'url' => $base]);
        yield new SchemaNode(['@type' => 'Organization', '@id' => $base.'#organization']);
        yield new SchemaNode([
            '@type' => 'Article',
            '@id' => $base.'#article',
            'headline' => $document->title ?? $context->title,
            'datePublished' => $context->publishedAt?->format(DATE_ATOM),
            'dateModified' => $context->updatedAt?->format(DATE_ATOM),
        ]);
        yield new SchemaNode([
            '@type' => 'BreadcrumbList',
            '@id' => $base.'#breadcrumb',
            'itemListElement' => array_map(
                static fn (mixed $term, int $position): array => [
                    '@type' => 'ListItem',
                    'position' => $position + 1,
                    'name' => (string) $term,
                ],
                $context->terms,
                array_keys($context->terms),
            ),
        ]);
    }
}
