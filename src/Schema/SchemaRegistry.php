<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Schema;

use Jenlut\YoastSeoLaravel\Contracts\SchemaProvider;

final readonly class SchemaRegistry
{
    /** @param list<SchemaProvider> $providers */
    public function __construct(private array $providers = []) {}

    /** @return list<SchemaProvider> */
    public function providers(): array
    {
        return $this->providers;
    }
}
