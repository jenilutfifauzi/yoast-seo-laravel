<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Indexables\IndexableMode;
use Jenlut\YoastSeoLaravel\Indexables\IndexableResolver;
use Jenlut\YoastSeoLaravel\Indexables\StatelessIndexableRepository;

it('selects stateless mode without opening a database connection', function () {
    config(['yoast-seo.indexables.enabled' => false]);

    $resolver = app(IndexableResolver::class);

    expect($resolver->mode())->toBe(IndexableMode::STATELESS)
        ->and($resolver->repository())->toBeInstanceOf(StatelessIndexableRepository::class);
});
