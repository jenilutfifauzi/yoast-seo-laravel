<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Indexables;

use Illuminate\Support\Carbon;
use Jenlut\YoastSeoLaravel\Contracts\IndexableRepository;
use Jenlut\YoastSeoLaravel\Data\CanonicalUrl;
use Jenlut\YoastSeoLaravel\Data\IndexableData;
use Jenlut\YoastSeoLaravel\Data\RobotsDirective;
use Jenlut\YoastSeoLaravel\Models\Indexable;

final readonly class EloquentIndexableRepository implements IndexableRepository
{
    public function __construct(private Indexable $model = new Indexable) {}

    public function findByIdentity(string $type, string $id): ?IndexableData
    {
        $record = $this->model->newQuery()
            ->where('object_type', $type)
            ->where('object_id', $id)
            ->first();

        return $record === null ? null : $this->toData($record);
    }

    public function findByPermalinkHash(string $hash): ?IndexableData
    {
        $record = $this->model->newQuery()
            ->where('permalink_hash', $hash)
            ->first();

        return $record === null ? null : $this->toData($record);
    }

    public function save(IndexableData $data): IndexableData
    {
        $record = $this->model->newQuery()->updateOrCreate(
            [
                'object_type' => $data->objectType,
                'object_id' => $data->objectId,
            ],
            [
                'permalink' => $data->permalink,
                'permalink_hash' => $data->permalinkHash,
                'title' => $data->title,
                'description' => $data->description,
                'canonical' => $data->canonical?->value,
                'robots' => $data->robots === null ? null : (string) $data->robots,
                'public' => $data->public,
                'open_graph' => $data->openGraph,
                'twitter' => $data->twitter,
                'schema' => $data->schema,
                'indexed_at' => Carbon::now(),
            ],
        );

        return $this->toData($record);
    }

    public function deleteByIdentity(string $type, string $id): void
    {
        $this->model->newQuery()
            ->where('object_type', $type)
            ->where('object_id', $id)
            ->delete();
    }

    private function toData(Indexable $record): IndexableData
    {
        $canonical = $record->canonical === null
            ? null
            : CanonicalUrl::fromString((string) $record->canonical);
        $robots = $record->robots === null
            ? null
            : RobotsDirective::fromString((string) $record->robots);

        return new IndexableData(
            objectType: (string) $record->object_type,
            objectId: (string) $record->object_id,
            permalink: $record->permalink === null ? null : (string) $record->permalink,
            permalinkHash: (string) $record->permalink_hash,
            title: $record->title === null ? null : (string) $record->title,
            description: $record->description === null ? null : (string) $record->description,
            canonical: $canonical,
            robots: $robots,
            public: (bool) $record->public,
            openGraph: $this->associativeArray($record->open_graph),
            twitter: $this->associativeArray($record->twitter),
            schema: $this->schemaArray($record->schema),
        );
    }

    /** @return array<string, mixed> */
    private function associativeArray(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $result = [];

        foreach ($value as $key => $item) {
            if (is_string($key)) {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    /** @return array<string, mixed>|list<array<string, mixed>> */
    private function schemaArray(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        if (! array_is_list($value)) {
            return $this->associativeArray($value);
        }

        $result = [];

        foreach ($value as $item) {
            if (is_array($item)) {
                $result[] = $this->associativeArray($item);
            }
        }

        return $result;
    }
}

// ponytail: one model/repository boundary is enough until hierarchy consumers exist.
