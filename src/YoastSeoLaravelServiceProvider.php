<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel;

use Illuminate\Support\ServiceProvider;
use Jenlut\YoastSeoLaravel\Console\Commands\YoastSeoLaravelCommand;

class YoastSeoLaravelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/yoast-seo.php', 'yoast-seo');

        $this->app->singleton(YoastSeoLaravel::class);
        $this->app->singleton(SeoManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/yoast-seo-laravel.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'yoast-seo');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'yoast-seo');

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/yoast-seo.php' => config_path('yoast-seo.php'),
        ], ['yoast-seo', 'yoast-seo-config']);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/yoast-seo'),
        ], ['yoast-seo', 'yoast-seo-views']);

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/yoast-seo'),
        ], ['yoast-seo', 'yoast-seo-lang']);

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/yoast-seo'),
        ], ['yoast-seo', 'yoast-seo-assets']);

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['yoast-seo', 'yoast-seo-migrations']);

        $this->commands([
            YoastSeoLaravelCommand::class,
        ]);
    }
}
