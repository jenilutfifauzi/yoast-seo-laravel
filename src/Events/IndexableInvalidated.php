<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Events;

final readonly class IndexableInvalidated
{
    public function __construct(
        public string $objectType,
        public string $objectId,
    ) {}
}

// ponytail: invalidation needs identity only; no deleted row snapshot.
