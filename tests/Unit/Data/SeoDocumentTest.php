<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Data\CanonicalUrl;
use Jenlut\YoastSeoLaravel\Data\RobotsDirective;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Data\SeoImage;

it('normalizes empty metadata and preserves structured values', function () {
    $document = new SeoDocument(
        title: 'Title',
        description: '',
        canonical: null,
        robots: new RobotsDirective(['index', 'follow']),
        openGraph: ['title' => 'Social title'],
        twitter: ['card' => 'summary_large_image'],
        schema: ['@type' => 'WebPage'],
    );

    expect($document->title)->toBe('Title')
        ->and($document->description)->toBeNull()
        ->and($document->openGraph)->toBe(['title' => 'Social title'])
        ->and($document->twitter)->toBe(['card' => 'summary_large_image'])
        ->and($document->schema)->toBe(['@type' => 'WebPage']);
});

it('creates immutable replacements without changing the original', function () {
    $original = SeoDocument::empty();
    $updated = $original->withTitle('New title');

    expect($original->title)->toBeNull()
        ->and($updated->title)->toBe('New title')
        ->and($updated)->not->toBe($original);
});

it('normalizes and validates robots directives', function () {
    $robots = RobotsDirective::fromString(' INDEX, follow, noindex ');

    expect($robots->tokens())->toBe(['index', 'follow', 'noindex'])
        ->and((string) $robots)->toBe('index,follow,noindex');

    expect(fn () => RobotsDirective::fromString('index,execute'))
        ->toThrow(InvalidArgumentException::class);
});

it('validates canonical and image URLs', function () {
    expect(CanonicalUrl::fromString('https://example.test/page')->value)
        ->toBe('https://example.test/page')
        ->and(SeoImage::fromString('https://example.test/image.jpg')->url)
        ->toBe('https://example.test/image.jpg');

    expect(fn () => CanonicalUrl::fromString('javascript:alert(1)'))
        ->toThrow(InvalidArgumentException::class)
        ->and(fn () => SeoImage::fromString('/relative-image.jpg'))
        ->toThrow(InvalidArgumentException::class);
});
