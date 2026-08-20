<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Schema\SchemaGraph;
use Jenlut\YoastSeoLaravel\Schema\SchemaNode;

it('deduplicates graph nodes by id deterministically', function () {
    $graph = new SchemaGraph;
    $graph->add(new SchemaNode(['@type' => 'Article', '@id' => 'https://example.test/#article', 'headline' => 'Old']));
    $graph->add(new SchemaNode(['@type' => 'Article', '@id' => 'https://example.test/#article', 'headline' => 'New']));
    $graph->add(new SchemaNode(['@type' => 'WebPage', '@id' => 'https://example.test/#page']));

    expect($graph->nodes())->toHaveCount(2)
        ->and($graph->nodes()[0]->toArray()['headline'])->toBe('New');
});

it('encodes a JSON-LD graph with JSON_THROW_ON_ERROR', function () {
    $graph = new SchemaGraph;
    $graph->add(new SchemaNode(['@type' => 'WebPage', '@id' => 'https://example.test/#page']));

    $decoded = json_decode($graph->toJson(), true, 512, JSON_THROW_ON_ERROR);

    expect($decoded['@context'])->toBe('https://schema.org')
        ->and($decoded['@graph'])->toHaveCount(1);
});
