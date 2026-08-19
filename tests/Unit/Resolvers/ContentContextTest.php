<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Data\ContentContext;

it('normalizes generic content context fields immutably', function () {
    $context = new ContentContext(
        type: 'post',
        identifier: ' 42 ',
        url: 'https://example.test/posts/42',
        title: '  Hello ',
        body: 'Body',
        author: ['name' => 'Jen'],
        terms: ['news'],
    );

    expect($context->type)->toBe('post')
        ->and($context->identifier)->toBe('42')
        ->and($context->title)->toBe('Hello')
        ->and($context->url)->toBe('https://example.test/posts/42')
        ->and($context->author)->toBe(['name' => 'Jen'])
        ->and($context->terms)->toBe(['news']);
});

it('rejects an empty type or identifier', function () {
    expect(fn () => new ContentContext(type: '', identifier: '1'))
        ->toThrow(InvalidArgumentException::class);

    expect(fn () => new ContentContext(type: 'post', identifier: ''))
        ->toThrow(InvalidArgumentException::class);
});

it('keeps arbitrary source data out of the public context contract', function () {
    $context = new ContentContext(type: 'page', identifier: 7, source: ['secret' => 'value']);

    expect($context->source)->toBe(['secret' => 'value'])
        ->and($context)->toBeInstanceOf(ContentContext::class);
});
