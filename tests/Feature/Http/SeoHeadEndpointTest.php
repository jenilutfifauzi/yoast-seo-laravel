<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

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
