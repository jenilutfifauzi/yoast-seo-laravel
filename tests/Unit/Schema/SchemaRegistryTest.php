<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Contracts\SchemaProvider;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Schema\SchemaGenerator;
use Jenlut\YoastSeoLaravel\Schema\SchemaNode;
use Jenlut\YoastSeoLaravel\Schema\SchemaRegistry;

final class FakeSchemaProvider implements SchemaProvider
{
    public function __construct(private readonly string $type, private readonly bool $supported = true) {}

    public function supports(ContentContext $context): bool
    {
        return $this->supported;
    }

    public function provide(ContentContext $context, SeoDocument $document): iterable
    {
        yield new SchemaNode(['@type' => $this->type, '@id' => '#'.$this->type]);
    }
}

it('preserves tagged provider order and filters unsupported providers', function () {
    $registry = new SchemaRegistry([
        new FakeSchemaProvider('First'),
        new FakeSchemaProvider('Skipped', false),
        new FakeSchemaProvider('Second'),
    ]);

    $nodes = (new SchemaGenerator($registry))->generate(
        new ContentContext('post', '1'),
        SeoDocument::empty(),
    )->nodes();

    expect($nodes)->toHaveCount(2)
        ->and($nodes[0]->type())->toBe('First')
        ->and($nodes[1]->type())->toBe('Second');
});

it('skips a failed optional provider without breaking graph generation', function () {
    $registry = new SchemaRegistry([
        new FakeSchemaProvider('First'),
        new class implements SchemaProvider
        {
            public function supports(ContentContext $context): bool
            {
                return true;
            }

            public function provide(ContentContext $context, SeoDocument $document): iterable
            {
                throw new RuntimeException('optional failure');
            }
        },
    ]);

    expect((new SchemaGenerator($registry))->generate(new ContentContext('post', '1'), SeoDocument::empty())->nodes())
        ->toHaveCount(1);
});
