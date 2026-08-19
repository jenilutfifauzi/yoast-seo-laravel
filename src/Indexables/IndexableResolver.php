<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Indexables;

use Jenlut\YoastSeoLaravel\Contracts\IndexableRepository;

final class IndexableResolver
{
    private readonly IndexableMode $selectedMode;

    public function __construct(?IndexableMode $mode = null)
    {
        $configured = config('yoast-seo.indexables.enabled', false);
        $configured = is_bool($configured) || is_string($configured) ? $configured : false;
        $this->selectedMode = $mode ?? IndexableMode::fromConfig($configured);
    }

    public function mode(): IndexableMode
    {
        return $this->selectedMode;
    }

    public function repository(): IndexableRepository
    {
        return $this->selectedMode === IndexableMode::INDEXED
            ? new InMemoryIndexableRepository
            : new StatelessIndexableRepository;
    }
}
