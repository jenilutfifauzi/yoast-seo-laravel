<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Contracts\SeoExtension;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Filters\SeoFilterPipeline;
use Jenlut\YoastSeoLaravel\Schema\SchemaGraph;
use Jenlut\YoastSeoLaravel\Schema\SchemaNode;

it('applies extensions in registration order', function () {
    $pipeline = new SeoFilterPipeline([
        new class implements SeoExtension
        {
            public function extend(SchemaGraph $graph, ContentContext $context, SeoDocument $document): SchemaGraph
            {
                return $graph->add(new SchemaNode(['@type' => 'First']));
            }
        },
        new class implements SeoExtension
        {
            public function extend(SchemaGraph $graph, ContentContext $context, SeoDocument $document): SchemaGraph
            {
                return $graph->add(new SchemaNode(['@type' => 'Second']));
            }
        },
    ]);

    $nodes = $pipeline->apply(new SchemaGraph, new ContentContext('post', '1'), SeoDocument::empty())->nodes();

    expect(array_map(static fn (SchemaNode $node): string => $node->type(), $nodes))
        ->toBe(['First', 'Second']);
});

it('skips an extension that fails without losing the graph', function () {
    $pipeline = new SeoFilterPipeline([
        new class implements SeoExtension
        {
            public function extend(SchemaGraph $graph, ContentContext $context, SeoDocument $document): SchemaGraph
            {
                throw new RuntimeException('optional extension failure');
            }
        },
    ]);

    expect($pipeline->apply(new SchemaGraph, new ContentContext('post', '1'), SeoDocument::empty())->nodes())
        ->toBe([]);
});
