<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Schema;

final class SchemaGraph
{
    /** @var array<string, SchemaNode> */
    private array $nodes = [];

    public function add(SchemaNode $node): self
    {
        $key = $node->id() ?? 'anonymous:'.count($this->nodes);
        $this->nodes[$key] = $node;

        return $this;
    }

    /** @return list<SchemaNode> */
    public function nodes(): array
    {
        return array_values($this->nodes);
    }

    public function toJson(): string
    {
        return json_encode([
            '@context' => 'https://schema.org',
            '@graph' => array_map(
                static fn (SchemaNode $node): array => $node->toArray(),
                $this->nodes(),
            ),
        ], JSON_THROW_ON_ERROR
            | JSON_UNESCAPED_SLASHES
            | JSON_HEX_TAG
            | JSON_HEX_AMP
            | JSON_HEX_APOS
            | JSON_HEX_QUOT);
    }
}
