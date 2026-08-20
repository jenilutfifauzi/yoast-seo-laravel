<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Schema\Providers\DefaultSchemaProvider;
use Jenlut\YoastSeoLaravel\Schema\SchemaGenerator;
use Jenlut\YoastSeoLaravel\Schema\SchemaRegistry;
use Jenlut\YoastSeoLaravel\SeoManager;

it('renders default generated schema for a content context', function () {
    $manager = new SeoManager(
        schemaGenerator: new SchemaGenerator(new SchemaRegistry([new DefaultSchemaProvider])),
    );

    $html = $manager->for(new ContentContext(
        type: 'post',
        identifier: '1',
        url: 'https://example.test/posts/1',
        title: 'Context title',
        body: 'Context description',
    ))->render();

    expect($html)
        ->toContain('<script type="application/ld+json">')
        ->toContain('"@type":"WebPage"')
        ->toContain('"@type":"BreadcrumbList"');
});
