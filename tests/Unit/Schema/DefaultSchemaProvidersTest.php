<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Schema\Providers\DefaultSchemaProvider;

it('covers the default Yoast-style schema node types', function () {
    $nodes = iterator_to_array((new DefaultSchemaProvider)->provide(
        new ContentContext('post', '1', 'https://example.test/post/1', 'Title', 'Body'),
        new SeoDocument(title: 'SEO title', description: 'SEO description'),
    ));

    expect(array_map(static fn ($node) => $node->type(), $nodes))
        ->toBe(['WebPage', 'WebSite', 'Organization', 'Article', 'BreadcrumbList']);
});

it('does not emit route-less graph nodes', function () {
    expect((new DefaultSchemaProvider)->supports(new ContentContext('post', '1')))->toBeFalse();
});
