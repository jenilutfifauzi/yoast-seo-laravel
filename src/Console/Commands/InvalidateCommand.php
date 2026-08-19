<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Console\Commands;

use Illuminate\Console\Command;

final class InvalidateCommand extends Command
{
    protected $signature = 'yoast-seo:invalidate {--type= : Content type} {--id= : Content identifier}';

    protected $description = 'Invalidate one SEO indexable identity.';

    public function handle(): int
    {
        if ($this->option('type') === null || $this->option('id') === null) {
            $this->error('Both --type and --id are required.');

            return self::INVALID;
        }

        $this->info('Indexable invalidation requested.');

        return self::SUCCESS;
    }
}
