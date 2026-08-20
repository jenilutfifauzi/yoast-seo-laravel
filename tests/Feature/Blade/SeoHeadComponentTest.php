<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;

it('renders the package head component with an explicit document', function () {
    $view = view('yoast-seo::components.head', [
        'document' => new SeoDocument(title: 'Blade title', description: 'Blade description'),
    ]);

    expect($view->render())
        ->toContain('<title>Blade title</title>')
        ->toContain('name="description"')
        ->toContain('Blade description');
});

it('renders the documented Blade component alias', function () {
    $html = Blade::render(
        '<x-yoast-seo::head :document="$document" />',
        ['document' => new SeoDocument(title: 'Alias title')],
    );

    expect($html)->toContain('<title>Alias title</title>');
});

it('renders generated schema for an explicit content context', function () {
    $html = Blade::render(
        '<x-yoast-seo::head :content="$content" />',
        ['content' => new ContentContext('post', '1', 'https://example.test/posts/1')],
    );

    expect($html)
        ->toContain('<script type="application/ld+json">')
        ->toContain('"@type":"WebPage"');
});

it('renders no empty metadata when the component receives no document', function () {
    expect(view('yoast-seo::components.head')->render())->toBe('');
});

it('publishes the package view override under the final namespace', function () {
    $this->artisan('vendor:publish', [
        '--tag' => 'yoast-seo-views',
        '--force' => true,
    ])->assertSuccessful();

    expect(is_dir(resource_path('views/vendor/yoast-seo')))->toBeTrue();
});
