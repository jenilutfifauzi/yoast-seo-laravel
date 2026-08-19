<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Indexables;

use Jenlut\YoastSeoLaravel\Contracts\IndexableRepository;
use Jenlut\YoastSeoLaravel\Data\IndexableData;

final class InMemoryIndexableRepository implements IndexableRepository
{
    /** @var array<string, IndexableData> */
    private array $items = [];

    public function findByIdentity(string $type, string $id): ?IndexableData
    {
        return $this->items[$this->identity($type, $id)] ?? null;
    }

    public function findByPermalinkHash(string $hash): ?IndexableData
    {
        foreach ($this->items as $item) {
            if ($item->permalinkHash === $hash) {
                return $item;
            }
        }

        return null;
    }

    public function save(IndexableData $data): IndexableData
    {
        $this->items[$this->identity($data->objectType, $data->objectId)] = $data;

        return $data;
    }

    public function deleteByIdentity(string $type, string $id): void
    {
        unset($this->items[$this->identity($type, $id)]);
    }

    private function identity(string $type, string $id): string
    {
        return $type.':'.$id;
    }
}

// ponytail: in-memory indexed mode keeps package tests and non-DB consumers deterministic.
