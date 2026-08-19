<?php

declare(strict_types=1);

namespace YoastSeoLaravel\YoastSeoLaravel\Console\Commands;

use Illuminate\Console\Command;

class YoastSeoLaravelCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'yoast-seo-laravel:placeholder';

    /**
     * The command description.
     */
    protected $description = 'Placeholder Artisan command shipped by the package yoast-seo-laravel.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->line('YoastSeoLaravel placeholder command executed.');

        return self::SUCCESS;
    }
}
