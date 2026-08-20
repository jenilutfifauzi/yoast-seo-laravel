<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Schema;

use Illuminate\Contracts\Events\Dispatcher;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Events\SchemaGraphResolving;
use Jenlut\YoastSeoLaravel\Filters\SeoFilterPipeline;
use Throwable;

final readonly class SchemaGenerator
{
    public function __construct(
        private SchemaRegistry $registry,
        private ?SeoFilterPipeline $filters = null,
        private ?Dispatcher $events = null,
    ) {}

    public function generate(ContentContext $context, SeoDocument $document): SchemaGraph
    {
        $graph = new SchemaGraph;

        foreach ($this->registry->providers() as $provider) {
            try {
                if (! $provider->supports($context)) {
                    continue;
                }

                $staged = new SchemaGraph;

                foreach ($provider->provide($context, $document) as $node) {
                    $staged->add($node);
                }

                foreach ($staged->nodes() as $node) {
                    $graph->add($node);
                }
            } catch (Throwable) {
                // ponytail: optional providers fail closed; core metadata remains renderable.
                continue;
            }
        }

        if ($this->filters !== null) {
            $graph = $this->filters->apply($graph, $context, $document);
        }

        if ($this->events !== null) {
            $event = new SchemaGraphResolving($graph, $context, $document);
            $this->events->dispatch($event);
            $graph = $event->graph;
        }

        return $graph;
    }
}
