<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Jenlut\YoastSeoLaravel\Contracts\IndexableRepository;
use Jenlut\YoastSeoLaravel\Data\CanonicalUrl;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\IndexableData;
use Jenlut\YoastSeoLaravel\Indexables\DefaultIndexableBuilder;
use Jenlut\YoastSeoLaravel\Indexables\InMemoryIndexableRepository;
use Jenlut\YoastSeoLaravel\SeoManager;

it('uses stored indexable metadata for content head output', function () {
    $repository = new InMemoryIndexableRepository;
    $repository->save(new IndexableData(
        objectType: 'post',
        objectId: '1',
        permalink: 'https://example.test/posts/1',
        permalinkHash: hash('sha256', 'https://example.test/posts/1'),
        title: 'Indexed title',
        description: 'Indexed description',
        canonical: CanonicalUrl::fromString('https://example.test/indexed'),
    ));
    $this->app->instance(IndexableRepository::class, $repository);

    $html = Blade::render(
        '<x-yoast-seo::head :content="$content" />',
        ['content' => new ContentContext('post', '1', 'https://example.test/posts/1')],
    );

    expect($html)
        ->toContain('<title>Indexed title</title>')
        ->toContain('content="Indexed description"')
        ->toContain('href="https://example.test/indexed"');
});

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
