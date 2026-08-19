<?php

declare(strict_types=1);

it('merges the final Yoast SEO configuration with cache-safe defaults', function () {
    expect(config('yoast-seo'))
        ->toMatchArray([
            'enabled' => true,
            'title' => [
                'separator' => ' | ',
                'default' => null,
                'suffix' => null,
            ],
            'description' => [
                'default' => null,
                'max_length' => 160,
            ],
            'canonical' => [
                'enabled' => true,
                'force_https' => false,
            ],
            'robots' => ['default' => 'index,follow'],
            'open_graph' => [
                'enabled' => true,
                'site_name' => null,
                'default_image' => null,
            ],
            'twitter' => [
                'enabled' => true,
                'card' => 'summary_large_image',
                'site' => null,
                'creator' => null,
            ],
            'schema' => ['enabled' => true],
            'indexables' => [
                'enabled' => false,
                'table' => 'yoast_seo_indexables',
                'queue' => false,
            ],
            'sitemap' => [
                'enabled' => false,
                'path' => 'sitemap.xml',
                'cache_seconds' => 3600,
            ],
            'analysis' => [
                'enabled' => true,
                'expose_keyphrase_publicly' => false,
            ],
            'cache' => [
                'enabled' => true,
                'store' => null,
                'prefix' => 'yoast-seo',
            ],
        ]);
});

it('does not contain closures in the package configuration', function () {
    $containsClosure = function (mixed $value) use (&$containsClosure): bool {
        if ($value instanceof Closure) {
            return true;
        }

        if (! is_array($value)) {
            return false;
        }

        foreach ($value as $nested) {
            if ($containsClosure($nested)) {
                return true;
            }
        }

        return false;
    };

    expect($containsClosure(config('yoast-seo')))->toBeFalse();
});

it('publishes the final config under the documented tag', function () {
    $this->artisan('vendor:publish', [
        '--tag' => 'yoast-seo-config',
        '--force' => true,
    ])->assertSuccessful();

    expect(file_exists(config_path('yoast-seo.php')))->toBeTrue();
});

it('keeps the old skeleton config key out of the public package contract', function () {
    expect(config('yoast-seo-laravel'))->toBeNull();
});

it('keeps the package config file loadable as plain PHP data', function () {
    expect(require dirname(__DIR__, 2).'/config/yoast-seo.php')->toBeArray();
});
