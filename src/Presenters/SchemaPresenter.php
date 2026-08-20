<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Presenters;

use Jenlut\YoastSeoLaravel\Schema\SchemaGraph;
use Jenlut\YoastSeoLaravel\Schema\SchemaNode;

final class SchemaPresenter
{
    /**
     * @param  iterable<SchemaNode|array<string, mixed>>  $nodes
     */
    public function present(iterable $nodes): string
    {
        $graph = new SchemaGraph;

        foreach ($nodes as $node) {
            $graph->add($node instanceof SchemaNode ? $node : new SchemaNode($node));
        }

        if ($graph->nodes() === []) {
            return '';
        }

        $payload = array_map(
            static fn (SchemaNode $node): array => $node->toArray(),
            $graph->nodes(),
        );

        $json = json_encode([
            '@context' => 'https://schema.org',
            '@graph' => $payload,
        ], JSON_THROW_ON_ERROR
            | JSON_UNESCAPED_SLASHES
            | JSON_HEX_TAG
            | JSON_HEX_AMP
            | JSON_HEX_APOS
            | JSON_HEX_QUOT);

        return '<script type="application/ld+json">'.$json.'</script>';
    }
}

// ponytail: explicit nodes keep head rendering deterministic; provider generation remains a separate concern.
