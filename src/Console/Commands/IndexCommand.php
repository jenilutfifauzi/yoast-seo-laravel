<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Jenlut\YoastSeoLaravel\Contracts\IndexableBuilder;
use Jenlut\YoastSeoLaravel\Contracts\IndexableRepository;
use Jenlut\YoastSeoLaravel\Contracts\IndexableSource;
use Jenlut\YoastSeoLaravel\Events\IndexableUpdated;

final class IndexCommand extends Command
{
    protected $signature = 'yoast-seo:index {--type= : Optional provider content type}';

    protected $description = 'Rebuild SEO indexables from registered content providers.';

    public function __construct(
        private readonly IndexableRepository $repository,
        private readonly IndexableBuilder $builder,
        private readonly Dispatcher $events,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $sources = app()->tagged('yoast-seo.indexable-sources');

        if ($sources === []) {
            $this->info('No indexable sources are registered.');

            return self::SUCCESS;
        }

        $indexed = 0;

        $typeOption = $this->option('type');
        $type = is_string($typeOption) && trim($typeOption) !== '' ? trim($typeOption) : null;

        foreach ($sources as $source) {
            if (! $source instanceof IndexableSource) {
                continue;
            }

            foreach ($source->contexts($type) as $context) {
                $data = $this->repository->save($this->builder->build($context));
                $this->events->dispatch(new IndexableUpdated($data));
                $indexed++;
            }
        }

        $this->info("Indexed {$indexed} content item(s).");

        return self::SUCCESS;
    }
}

// ponytail: providers own enumeration; never scan arbitrary application models.
