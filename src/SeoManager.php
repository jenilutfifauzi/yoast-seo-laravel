<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel;

use Jenlut\YoastSeoLaravel\Data\CanonicalUrl;
use Jenlut\YoastSeoLaravel\Data\IndexableData;
use Jenlut\YoastSeoLaravel\Data\RobotsDirective;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Presenters\SeoHeadPresenter;

class SeoManager implements Contracts\SeoManager
{
    private SeoDocument $document;

    private string|int|object|null $content = null;

    public function __construct(?SeoDocument $document = null)
    {
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

    /** @param array<string, mixed> $schema */
    public function schema(array $schema): self
    {
        return $this->withDocument($this->document->withSchema($schema));
    }

    public function document(): SeoDocument
    {
        return $this->document;
    }

    public function fromIndexable(IndexableData $data): self
    {
        $manager = clone $this;
        $manager->document = new SeoDocument(
            title: $data->title,
            description: $data->description,
            canonical: $data->canonical,
            robots: $data->robots,
            openGraph: $data->openGraph,
            twitter: $data->twitter,
            schema: $data->schema,
        );

        return $manager;
    }

    public function render(): string
    {
        return (new SeoHeadPresenter)->present($this->document);
    }

    private function withDocument(SeoDocument $document): self
    {
        $manager = clone $this;
        $manager->document = $document;

        return $manager;
    }
}
