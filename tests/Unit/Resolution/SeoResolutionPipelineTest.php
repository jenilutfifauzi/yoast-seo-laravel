<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Pipeline\SeoResolutionPipeline;
use Jenlut\YoastSeoLaravel\Resolvers\DefaultSeoResolver;
use Jenlut\YoastSeoLaravel\Resolvers\RequestSeoResolver;

it('resolves explicit values before resolver values and config defaults', function () {
    $pipeline = new SeoResolutionPipeline([
        new RequestSeoResolver([
            'title' => 'Request title',
            'description' => 'Request description',
        ]),
        new DefaultSeoResolver([
            'title' => 'Resolver title',
            'description' => 'Resolver description',
            'robots' => 'noindex,nofollow',
        ]),
    ]);

    $document = $pipeline->resolve(new SeoDocument(
        title: 'Explicit title',
        description: null,
    ));

    expect($document->title)->toBe('Explicit title')
        ->and($document->description)->toBe('Request description')
        ->and((string) $document->robots)->toBe('noindex,nofollow');
});

it('falls back to config defaults when no resolver supplies a value', function () {
    $document = (new SeoResolutionPipeline([
        new DefaultSeoResolver([]),
    ]))->resolve(SeoDocument::empty());

    expect($document->title)->toBeNull()
        ->and($document->description)->toBeNull()
        ->and((string) $document->robots)->toBe('index,follow');
});

it('does not mutate the input document', function () {
    $input = SeoDocument::empty();
    $resolved = (new SeoResolutionPipeline([
        new RequestSeoResolver(['title' => 'Resolved']),
    ]))->resolve($input);

    expect($input->title)->toBeNull()
        ->and($resolved->title)->toBe('Resolved');
});
