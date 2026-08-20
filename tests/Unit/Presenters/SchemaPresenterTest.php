<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Presenters\SchemaPresenter;
use Jenlut\YoastSeoLaravel\Schema\SchemaValidationException;

it('renders schema nodes as an escaped JSON-LD script', function () {
    $html = (new SchemaPresenter)->present([
        [
            '@type' => 'Article',
            '@id' => 'https://example.test/#article',
            'headline' => '</script><script>alert(1)</script>',
        ],
    ]);

    expect($html)
        ->toStartWith('<script type="application/ld+json">')
        ->toEndWith('</script>')
        ->not->toContain('</script><script>')
        ->toContain('Article');
});

it('rejects invalid schema node values', function () {
    expect(fn () => (new SchemaPresenter)->present([
        ['@type' => 'Article', 'author' => new stdClass],
    ]))->toThrow(SchemaValidationException::class);
});

it('returns an empty string when no schema nodes exist', function () {
    expect((new SchemaPresenter)->present([]))->toBe('');
});
