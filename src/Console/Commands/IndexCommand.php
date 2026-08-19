<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Console\Commands;

use Illuminate\Console\Command;

final class IndexCommand extends Command
{
    protected $signature = 'yoast-seo:index {--type= : Optional provider content type}';

    protected $description = 'Rebuild SEO indexables from registered content providers.';

    public function handle(): int
    {
        $this->info('No indexable providers are registered.');

        return self::SUCCESS;
    }
}

// ponytail: providers own enumeration; never scan arbitrary application models.
