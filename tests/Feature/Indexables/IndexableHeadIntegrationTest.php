<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Indexables\DefaultIndexableBuilder;
use Jenlut\YoastSeoLaravel\SeoManager;

it('maps a generic content context into SEO head output', function () {
    $context = new ContentContext(
        type: 'post',
        identifier: '1',
        url: 'https://example.test/posts/1',
        title: 'Context title',
        body: 'Context description',
    );

    $data = (new DefaultIndexableBuilder)->build($context);
    $manager = (new SeoManager)->fromIndexable($data);

    expect($manager->render())
        ->toContain('<title>Context title</title>')
        ->toContain('content="Context description"')
        ->toContain('href="https://example.test/posts/1"');
});

it('keeps explicit manager metadata above indexable metadata', function () {
    $data = (new DefaultIndexableBuilder)->build(new ContentContext(
        type: 'post',
        identifier: '1',
        url: 'https://example.test/posts/1',
        title: 'Context title',
    ));

    expect((new SeoManager)->fromIndexable($data)->title('Explicit title')->render())
        ->toContain('<title>Explicit title</title>')
        ->not->toContain('<title>Context title</title>');
});
