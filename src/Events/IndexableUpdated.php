<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Events;

use Jenlut\YoastSeoLaravel\Data\IndexableData;

final readonly class IndexableUpdated
{
    public function __construct(public IndexableData $indexable) {}
}

// ponytail: event carries the normalized DTO; listeners do not need the source model.
