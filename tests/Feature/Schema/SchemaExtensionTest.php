<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Jenlut\YoastSeoLaravel\Contracts\SeoExtension;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Events\SchemaGraphResolving;
use Jenlut\YoastSeoLaravel\Filters\SeoFilterPipeline;
use Jenlut\YoastSeoLaravel\Schema\SchemaGenerator;
use Jenlut\YoastSeoLaravel\Schema\SchemaGraph;
use Jenlut\YoastSeoLaravel\Schema\SchemaNode;

it('applies tagged extensions and dispatched graph listeners', function () {
    $extension = new class implements SeoExtension
    {
        public function extend(SchemaGraph $graph, ContentContext $context, SeoDocument $document): SchemaGraph
        {
            return $graph->add(new SchemaNode(['@type' => 'ExtensionNode']));
        }
    };

    $this->app->instance(SeoFilterPipeline::class, new SeoFilterPipeline([$extension]));

    Event::listen(SchemaGraphResolving::class, static function (SchemaGraphResolving $event): void {
        $event->graph->add(new SchemaNode(['@type' => 'EventNode']));
    });

    $nodes = $this->app->make(SchemaGenerator::class)->generate(
        new ContentContext('post', '1', 'https://example.test/post/1'),
        SeoDocument::empty(),
    )->nodes();

    expect(array_map(static fn (SchemaNode $node): string => $node->type(), $nodes))
        ->toContain('ExtensionNode')
        ->toContain('EventNode');
});
