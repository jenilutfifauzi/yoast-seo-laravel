<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Contracts;

use Jenlut\YoastSeoLaravel\Data\IndexableData;

interface IndexableRepository
{
    public function findByIdentity(string $type, string $id): ?IndexableData;

    public function findByPermalinkHash(string $hash): ?IndexableData;

    public function save(IndexableData $data): IndexableData;

    public function deleteByIdentity(string $type, string $id): void;
}

// ponytail: repository methods stay narrow until hierarchy/primary-term consumers exist.
