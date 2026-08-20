<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Contracts\SchemaProvider;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Schema\SchemaGenerator;
use Jenlut\YoastSeoLaravel\Schema\SchemaGraph;
use Jenlut\YoastSeoLaravel\Schema\SchemaNode;
use Jenlut\YoastSeoLaravel\Schema\SchemaRegistry;

it('deduplicates graph nodes by id deterministically', function () {
    $graph = new SchemaGraph;
    $graph->add(new SchemaNode(['@type' => 'Article', '@id' => 'https://example.test/#article', 'headline' => 'Old']));
    $graph->add(new SchemaNode(['@type' => 'Article', '@id' => 'https://example.test/#article', 'headline' => 'New']));
    $graph->add(new SchemaNode(['@type' => 'WebPage', '@id' => 'https://example.test/#page']));

    expect($graph->nodes())->toHaveCount(2)
        ->and($graph->nodes()[0]->toArray()['headline'])->toBe('New');
});

it('encodes a JSON-LD graph with JSON_THROW_ON_ERROR and script-safe escaping', function () {
    $graph = new SchemaGraph;
    $graph->add(new SchemaNode([
        '@type' => 'WebPage',
        '@id' => 'https://example.test/#page',
        'name' => '</script><script>alert(1)</script>',
    ]));

    $json = $graph->toJson();
    $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

    expect($decoded['@context'])->toBe('https://schema.org')
        ->and($decoded['@graph'])->toHaveCount(1)
        ->and($json)->not->toContain('</script>')
        ->and($json)->toContain('\\u003C/script\\u003E');
});

it('does not retain provider nodes when the provider fails mid-stream', function () {
    $provider = new class implements SchemaProvider
    {
        public function supports(ContentContext $context): bool
        {
            return true;
        }

        public function provide(ContentContext $context, SeoDocument $document): iterable
        {
            yield new SchemaNode(['@type' => 'Partial']);

            throw new RuntimeException('provider failed');
        }
    };

    $nodes = (new SchemaGenerator(
        new SchemaRegistry([$provider]),
    ))->generate(new ContentContext('post', '1'), SeoDocument::empty())->nodes();

    expect($nodes)->toBe([]);
});
