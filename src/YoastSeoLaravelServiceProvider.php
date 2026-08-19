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
        $this->mergeConfigFrom(__DIR__.'/../config/yoast-seo-laravel.php', 'yoast-seo-laravel');

        $this->app->singleton(YoastSeoLaravel::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/yoast-seo-laravel.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'yoast-seo-laravel');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'yoast-seo-laravel');

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/yoast-seo-laravel.php' => config_path('yoast-seo-laravel.php'),
        ], ['yoast-seo-laravel', 'yoast-seo-laravel-config']);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/yoast-seo-laravel'),
        ], ['yoast-seo-laravel', 'yoast-seo-laravel-views']);

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/yoast-seo-laravel'),
        ], ['yoast-seo-laravel', 'yoast-seo-laravel-lang']);

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/yoast-seo-laravel'),
        ], ['yoast-seo-laravel', 'yoast-seo-laravel-assets']);

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['yoast-seo-laravel', 'yoast-seo-laravel-migrations']);

        $this->commands([
            YoastSeoLaravelCommand::class,
        ]);
    }
}
