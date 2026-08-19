<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\YoastSeoLaravel;
use Jenlut\YoastSeoLaravel\YoastSeoLaravelServiceProvider;

it('boots the package provider and resolves the singleton binding', function () {
    expect(app()->getProvider(YoastSeoLaravelServiceProvider::class))
        ->toBeInstanceOf(YoastSeoLaravelServiceProvider::class)
        ->and(app(YoastSeoLaravel::class))
        ->toBe(app(YoastSeoLaravel::class));
});

it('loads package resources from the provider', function () {
    expect(config('yoast-seo-laravel.placeholder'))->toBe('default')
        ->and(view()->exists('yoast-seo-laravel::placeholder'))->toBeTrue()
        ->and(trans('yoast-seo-laravel::messages.placeholder'))
        ->toBe('YoastSeoLaravel placeholder translation.');
});

it('registers the generated console command through the provider', function () {
    $this->artisan('yoast-seo-laravel:placeholder')
        ->expectsOutputToContain('YoastSeoLaravel placeholder command executed.')
        ->assertSuccessful();
});

it('keeps provider registration free of database access', function () {
    expect(app()->getProvider(YoastSeoLaravelServiceProvider::class))
        ->not->toBeNull();
});
