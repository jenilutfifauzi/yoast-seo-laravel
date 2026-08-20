<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Facades\YoastSeo;
use Jenlut\YoastSeoLaravel\SeoManager;
use Jenlut\YoastSeoLaravel\YoastSeoLaravelServiceProvider;

function packageRoot(): string
{
    return dirname(__DIR__, 2);
}

function composerMetadata(): array
{
    return json_decode(
        file_get_contents(packageRoot().'/composer.json'),
        true,
        512,
        JSON_THROW_ON_ERROR,
    );
}

it('uses the final package identity and Laravel discovery metadata', function () {
    $composer = composerMetadata();

    expect($composer['name'])->toBe('jenilutfifauzi/yoast-seo-laravel')
        ->and($composer['autoload']['psr-4'])->toHaveKey('Jenlut\\YoastSeoLaravel\\', 'src/')
        ->and($composer['extra']['laravel']['providers'])
        ->toContain('Jenlut\\YoastSeoLaravel\\YoastSeoLaravelServiceProvider')
        ->and($composer['extra']['laravel']['aliases']['YoastSeo'])
        ->toBe('Jenlut\\YoastSeoLaravel\\Facades\\YoastSeo');
});

it('uses the final namespace in package source and tests', function () {
    foreach (['src', 'tests'] as $directory) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(packageRoot().'/'.$directory),
        );

        foreach ($iterator as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $source = file_get_contents($file->getPathname());
            $legacyNamespaceDeclaration = 'namespace YoastSeoLaravel'.'\\';
            $legacyImport = 'use YoastSeoLaravel'.'\\';

            expect($source)
                ->not->toContain($legacyNamespaceDeclaration)
                ->not->toContain($legacyImport);
        }
    }
});

it('resolves the final provider and facade classes', function () {
    expect(app()->getProvider(YoastSeoLaravelServiceProvider::class))
        ->not->toBeNull()
        ->and(YoastSeo::getFacadeRoot())
        ->toBeInstanceOf(SeoManager::class);
});

it('does not keep the generated skeleton identity', function () {
    expect(file_get_contents(packageRoot().'/composer.json'))
        ->not->toContain('jeni/yoast-seo-laravel')
        ->not->toContain('VendorName\\Skeleton');
});

it('keeps the package boot surface available', function () {
    expect(config('yoast-seo.enabled'))->toBeTrue();

    $this->artisan('yoast-seo-laravel:placeholder')->assertSuccessful();
});

it('keeps package metadata parseable', function () {
    expect(composerMetadata())->toBeArray();
});
