<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Schema\SchemaNode;
use Jenlut\YoastSeoLaravel\Schema\SchemaValidationException;

it('requires a schema type and preserves scalar/array properties', function () {
    $node = new SchemaNode([
        '@type' => 'Article',
        '@id' => 'https://example.test/#article',
        'headline' => 'A headline',
        'author' => ['@type' => 'Person', 'name' => 'Jen'],
    ]);

    expect($node->type())->toBe('Article')
        ->and($node->id())->toBe('https://example.test/#article')
        ->and($node->toArray()['headline'])->toBe('A headline');
});

it('rejects missing or invalid identity fields', function () {
    expect(fn () => new SchemaNode([]))
        ->toThrow(SchemaValidationException::class);

    expect(fn () => new SchemaNode(['@type' => 'Article', '@id' => 'javascript:alert(1)']))
        ->toThrow(SchemaValidationException::class);
});

it('rejects executable or object payload values', function () {
    expect(fn () => new SchemaNode([
        '@type' => 'Article',
        'unsafe' => static fn (): string => 'x',
    ]))->toThrow(SchemaValidationException::class);

    expect(fn () => new SchemaNode([
        '@type' => 'Article',
        'unsafe' => new stdClass,
    ]))->toThrow(SchemaValidationException::class);
});
