<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Contracts\SchemaProvider;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Schema\SchemaGenerator;
use Jenlut\YoastSeoLaravel\Schema\SchemaNode;
use Jenlut\YoastSeoLaravel\Schema\SchemaRegistry;

it('resolves tagged schema providers from the package container', function () {
    $provider = new class implements SchemaProvider
    {
        public function supports(ContentContext $context): bool
        {
            return $context->type === 'custom';
        }

        public function provide(ContentContext $context, SeoDocument $document): iterable
        {
            yield new SchemaNode([
                '@type' => 'Thing',
                '@id' => 'https://example.test/#thing',
                'name' => $document->title,
            ]);
        }
    };

    $this->app->tag($provider::class, 'yoast-seo.schema-providers');
    $this->app->bind($provider::class, static fn () => $provider);

    $graph = $this->app->make(SchemaGenerator::class)->generate(
        new ContentContext('custom', '1', 'https://example.test/custom', 'Context title'),
        new SeoDocument(title: 'Schema title'),
    );

    expect($graph->nodes())->toHaveCount(6)
        ->and(array_map(static fn (SchemaNode $node): string => $node->type(), $graph->nodes()))
        ->toContain('Thing');
});

it('registers the default provider in the schema registry', function () {
    expect($this->app->make(SchemaRegistry::class)->providers())->not->toBeEmpty();
});
