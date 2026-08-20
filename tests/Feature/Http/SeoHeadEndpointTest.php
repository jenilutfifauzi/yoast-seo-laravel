<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Jenlut\YoastSeoLaravel\Contracts\IndexableRepository;
use Jenlut\YoastSeoLaravel\Data\IndexableData;
use Jenlut\YoastSeoLaravel\Indexables\InMemoryIndexableRepository;

it('uses indexed metadata when content identity is provided', function () {
    $repository = new InMemoryIndexableRepository;
    $repository->save(new IndexableData(
        objectType: 'post',
        objectId: '1',
        permalink: 'https://example.test/indexed',
        permalinkHash: hash('sha256', 'https://example.test/indexed'),
        title: 'Indexed API title',
        description: 'Indexed API description',
    ));
    $this->app->instance(IndexableRepository::class, $repository);

    $response = $this->getJson('/yoast-seo/head?type=post&id=1');

    $response->assertSuccessful()
        ->assertJsonPath('data.title', 'Indexed API title')
        ->assertJsonPath('data.description', 'Indexed API description');
});

it('returns resolved SEO head JSON', function () {
    $response = $this->getJson('/yoast-seo/head?title=API%20title&description=API%20description');

    $response->assertSuccessful()
        ->assertJsonPath('data.title', 'API title')
        ->assertJsonPath('data.description', 'API description')
        ->assertJsonPath('data.html', "<title>API title</title>\n<meta name=\"description\" content=\"API description\">\n<meta name=\"robots\" content=\"index,follow\">");
});

it('rejects oversized metadata input', function () {
    $response = $this->getJson('/yoast-seo/head?title='.str_repeat('x', 501));

    $response->assertUnprocessable();
});

it('rejects invalid canonical URLs', function () {
    $response = $this->getJson('/yoast-seo/head?canonical=javascript%3Aalert(1)');

    $response->assertUnprocessable();
});

it('does not expose internal focus keyphrase data', function () {
    $response = $this->getJson('/yoast-seo/head?primary_keyphrase=laravel');

    $response->assertSuccessful()
        ->assertJsonMissingPath('data.primary_keyphrase')
        ->assertJsonMissingPath('data.focus_keyphrase');
});

it('registers the endpoint as a GET route without requiring a model class', function () {
    expect(Route::getRoutes()->getByName('yoast-seo.head'))->not->toBeNull();
});
