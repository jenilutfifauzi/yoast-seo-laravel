<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Data\CanonicalUrl;
use Jenlut\YoastSeoLaravel\Data\RobotsDirective;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Facades\YoastSeo;
use Jenlut\YoastSeoLaravel\SeoManager;

it('builds a document through the fluent manager API', function () {
    $manager = new SeoManager;
    $document = $manager->for('post:1')
        ->title('SEO title')
        ->description('SEO description')
        ->canonical('https://example.test/post/1')
        ->robots('index,follow')
        ->openGraph(['title' => 'Social title'])
        ->twitter(['card' => 'summary_large_image'])
        ->schema(['@type' => 'Article'])
        ->document();

    expect($document)->toBeInstanceOf(SeoDocument::class)
        ->and($document->title)->toBe('SEO title')
        ->and($document->description)->toBe('SEO description')
        ->and($document->canonical)->toEqual(CanonicalUrl::fromString('https://example.test/post/1'))
        ->and($document->robots)->toEqual(RobotsDirective::fromString('index,follow'))
        ->and($document->openGraph)->toBe(['title' => 'Social title'])
        ->and($document->twitter)->toBe(['card' => 'summary_large_image'])
        ->and($document->schema)->toBe(['@type' => 'Article']);
});

it('keeps content scopes isolated when switching with for', function () {
    $manager = new SeoManager;
    $first = $manager->for('post:1')->title('First');
    $second = $manager->for('post:2')->title('Second');

    expect($first->document()->title)->toBe('First')
        ->and($second->document()->title)->toBe('Second')
        ->and($first->document()->title)->not->toBe($second->document()->title);
});

it('resolves the manager as a singleton and facade root', function () {
    expect(app(SeoManager::class))->toBe(app(SeoManager::class))
        ->and(YoastSeo::getFacadeRoot())
        ->toBeInstanceOf(SeoManager::class);
});

it('rejects invalid metadata through the manager', function () {
    expect(fn () => (new SeoManager)->for('post:1')->canonical('javascript:alert(1)'))
        ->toThrow(InvalidArgumentException::class);
});
