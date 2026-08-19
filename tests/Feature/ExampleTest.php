<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\YoastSeoLaravel;

it('resolves the singleton', function () {
    expect(app(YoastSeoLaravel::class))->toBeInstanceOf(YoastSeoLaravel::class);
});

it('returns the same instance from the container', function () {
    expect(app(YoastSeoLaravel::class))->toBe(app(YoastSeoLaravel::class));
});

it('merges the package config', function () {
    expect(config('yoast-seo-laravel.placeholder'))->toBe('default');
});

it('loads the package translations', function () {
    expect(trans('yoast-seo-laravel::messages.placeholder'))->toBe('YoastSeoLaravel placeholder translation.');
});

it('loads the package views', function () {
    expect(view()->exists('yoast-seo-laravel::placeholder'))->toBeTrue();
});

it('registers the artisan command', function () {
    $this->artisan('yoast-seo-laravel:placeholder')
        ->expectsOutputToContain('YoastSeoLaravel placeholder command executed.')
        ->assertSuccessful();
});
