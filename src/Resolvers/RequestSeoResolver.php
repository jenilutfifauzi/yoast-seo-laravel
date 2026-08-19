<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Resolvers;

use Jenlut\YoastSeoLaravel\Contracts\SeoResolver;
use Jenlut\YoastSeoLaravel\Data\CanonicalUrl;
use Jenlut\YoastSeoLaravel\Data\RobotsDirective;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;

final class RequestSeoResolver implements SeoResolver
{
    /** @param array<string, mixed> $values */
    public function __construct(private readonly array $values) {}

    public function resolve(SeoDocument $document): SeoDocument
    {
        return $this->apply($document);
    }

    private function apply(SeoDocument $document): SeoDocument
    {
        if ($document->title === null && is_string($this->values['title'] ?? null)) {
            $document = $document->withTitle($this->values['title']);
        }

        if ($document->description === null && is_string($this->values['description'] ?? null)) {
            $document = $document->withDescription($this->values['description']);
        }

        if ($document->canonical === null && is_string($this->values['canonical'] ?? null)) {
            $document = $document->withCanonical(CanonicalUrl::fromString($this->values['canonical']));
        }

        if ($document->robots === null && is_string($this->values['robots'] ?? null)) {
            $document = $document->withRobots(RobotsDirective::fromString($this->values['robots']));
        }

        if ($document->openGraph === [] && is_array($this->values['open_graph'] ?? null)) {
            $document = $document->withOpenGraph($this->values['open_graph']);
        }

        if ($document->twitter === [] && is_array($this->values['twitter'] ?? null)) {
            $document = $document->withTwitter($this->values['twitter']);
        }

        if ($document->schema === [] && is_array($this->values['schema'] ?? null)) {
            $document = $document->withSchema($this->values['schema']);
        }

        return $document;
    }
}
