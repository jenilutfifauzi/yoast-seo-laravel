<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel;

use Jenlut\YoastSeoLaravel\Contracts\IndexableBuilder;
use Jenlut\YoastSeoLaravel\Contracts\IndexableRepository;
use Jenlut\YoastSeoLaravel\Data\CanonicalUrl;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\IndexableData;
use Jenlut\YoastSeoLaravel\Data\RobotsDirective;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Indexables\DefaultIndexableBuilder;
use Jenlut\YoastSeoLaravel\Indexables\StatelessIndexableRepository;
use Jenlut\YoastSeoLaravel\Presenters\SeoHeadPresenter;
use Jenlut\YoastSeoLaravel\Schema\SchemaGenerator;
use Jenlut\YoastSeoLaravel\Schema\SchemaNode;

class SeoManager implements Contracts\SeoManager
{
    private SeoDocument $document;

    private string|int|object|null $content = null;

    public function __construct(
        ?SeoDocument $document = null,
        private readonly ?SchemaGenerator $schemaGenerator = null,
        private readonly ?IndexableRepository $indexableRepository = null,
        private readonly ?IndexableBuilder $indexableBuilder = null,
    ) {
        $this->document = $document ?? SeoDocument::empty();
    }

    public function for(string|int|object|null $content): self
    {
        $manager = clone $this;
        $manager->content = $content;
        $manager->document = SeoDocument::empty();

        return $manager;
    }

    public function content(): string|int|object|null
    {
        return $this->content;
    }

    public function title(?string $title): self
    {
        return $this->withDocument($this->document->withTitle($title));
    }

    public function description(?string $description): self
    {
        return $this->withDocument($this->document->withDescription($description));
    }

    public function canonical(?string $canonical): self
    {
        return $this->withDocument(
            $this->document->withCanonical(
                $canonical === null ? null : CanonicalUrl::fromString($canonical),
            ),
        );
    }

    public function robots(?string $robots): self
    {
        return $this->withDocument(
            $this->document->withRobots(
                $robots === null ? null : RobotsDirective::fromString($robots),
            ),
        );
    }

    /** @param array<string, mixed> $openGraph */
    public function openGraph(array $openGraph): self
    {
        return $this->withDocument($this->document->withOpenGraph($openGraph));
    }

    /** @param array<string, mixed> $twitter */
    public function twitter(array $twitter): self
    {
        return $this->withDocument($this->document->withTwitter($twitter));
    }

    /** @param array<string, mixed>|list<array<string, mixed>> $schema */
    public function schema(array $schema): self
    {
        return $this->withDocument($this->document->withSchema($schema));
    }

    public function document(): SeoDocument
    {
        return $this->content instanceof ContentContext
            ? $this->resolveIndexable($this->document, $this->content)
            : $this->document;
    }

    public function fromIndexable(IndexableData $data): self
    {
        $manager = clone $this;
        $manager->document = new SeoDocument(
            title: $this->document->title ?? $data->title,
            description: $this->document->description ?? $data->description,
            canonical: $this->document->canonical ?? $data->canonical,
            robots: $this->document->robots ?? $data->robots,
            openGraph: $this->document->openGraph === [] ? $data->openGraph : $this->document->openGraph,
            twitter: $this->document->twitter === [] ? $data->twitter : $this->document->twitter,
            schema: $this->document->schema === [] ? $data->schema : $this->document->schema,
        );

        return $manager;
    }

    public function render(): string
    {
        $document = $this->document();

        if ($this->schemaGenerator !== null
            && $this->content instanceof ContentContext
            && $document->schema === []) {
            $document = $document->withSchema(array_map(
                static fn (SchemaNode $node): array => $node->toArray(),
                $this->schemaGenerator->generate($this->content, $document)->nodes(),
            ));
        }

        return (new SeoHeadPresenter)->present($document);
    }

    private function resolveIndexable(SeoDocument $document, ContentContext $context): SeoDocument
    {
        $repository = $this->indexableRepository ?? new StatelessIndexableRepository;
        $data = $repository->findByIdentity($context->type, $context->identifier);

        if ($data === null) {
            $builder = $this->indexableBuilder ?? new DefaultIndexableBuilder;
            $data = $repository->save($builder->build($context));
        }

        return $this->fromIndexable($data)->document;
    }

    private function withDocument(SeoDocument $document): self
    {
        $manager = clone $this;
        $manager->document = $document;

        return $manager;
    }
}
