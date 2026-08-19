<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Indexables;

use Jenlut\YoastSeoLaravel\Contracts\IndexableRepository;
use Jenlut\YoastSeoLaravel\Data\IndexableData;

final class StatelessIndexableRepository implements IndexableRepository
{
    public function findByIdentity(string $type, string $id): ?IndexableData
    {
        return null;
    }

    public function findByPermalinkHash(string $hash): ?IndexableData
    {
        return null;
    }

    public function save(IndexableData $data): IndexableData
    {
        return $data;
    }

    public function deleteByIdentity(string $type, string $id): void {}
}
