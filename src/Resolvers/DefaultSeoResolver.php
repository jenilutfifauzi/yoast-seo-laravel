<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Resolvers;

use Jenlut\YoastSeoLaravel\Contracts\SeoResolver;
use Jenlut\YoastSeoLaravel\Data\CanonicalUrl;
use Jenlut\YoastSeoLaravel\Data\RobotsDirective;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;

final class DefaultSeoResolver implements SeoResolver
{
    /** @param array<string, mixed>|null $defaults */
    public function __construct(?array $defaults = null)
    {
        $this->defaults = $defaults === null || $defaults === [] ? config('yoast-seo', []) : $defaults;
    }

    /** @var array<string, mixed> */
    private readonly array $defaults;

    public function resolve(SeoDocument $document): SeoDocument
    {
        $title = $this->value('title', 'default');
        $description = $this->value('description', 'default');
        $canonical = $this->value('canonical', 'default');
        $robots = $this->value('robots', 'default');

        if ($document->title === null && is_string($title)) {
            $document = $document->withTitle($title);
        }

        if ($document->description === null && is_string($description)) {
            $document = $document->withDescription($description);
        }

        if ($document->canonical === null && is_string($canonical)) {
            $document = $document->withCanonical(CanonicalUrl::fromString($canonical));
        }

        if ($document->robots === null && is_string($robots)) {
            $document = $document->withRobots(RobotsDirective::fromString($robots));
        }

        return $document;
    }

    private function value(string $key, string $nestedKey): mixed
    {
        $value = $this->defaults[$key] ?? null;

        if (is_array($value)) {
            return $value[$nestedKey] ?? null;
        }

        return $value;
    }
}
