<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Indexables\IndexableMode;

it('normalizes supported indexable modes', function () {
    expect(IndexableMode::fromConfig(false))->toBe(IndexableMode::STATELESS)
        ->and(IndexableMode::fromConfig(true))->toBe(IndexableMode::INDEXED)
        ->and(IndexableMode::fromConfig('indexed'))->toBe(IndexableMode::INDEXED);
});

it('rejects unknown indexable modes', function () {
    expect(fn () => IndexableMode::fromConfig('unknown'))
        ->toThrow(InvalidArgumentException::class);
});
