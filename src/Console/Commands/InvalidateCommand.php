<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Jenlut\YoastSeoLaravel\Contracts\IndexableRepository;
use Jenlut\YoastSeoLaravel\Events\IndexableInvalidated;

final class InvalidateCommand extends Command
{
    protected $signature = 'yoast-seo:invalidate {--type= : Content type} {--id= : Content identifier}';

    protected $description = 'Invalidate one SEO indexable identity.';

    public function __construct(
        private readonly IndexableRepository $repository,
        private readonly Dispatcher $events,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $typeOption = $this->option('type');
        $idOption = $this->option('id');
        $type = is_string($typeOption) ? trim($typeOption) : '';
        $id = is_string($idOption) ? trim($idOption) : '';

        if ($type === '' || $id === '') {
            $this->error('Both --type and --id are required.');

            return self::INVALID;
        }

        $this->repository->deleteByIdentity($type, $id);
        $this->events->dispatch(new IndexableInvalidated($type, $id));
        $this->info("Invalidated {$type}:{$id}.");

        return self::SUCCESS;
    }
}
