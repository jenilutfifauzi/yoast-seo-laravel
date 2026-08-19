<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Pipeline;

use Jenlut\YoastSeoLaravel\Contracts\SeoResolver;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;

final class SeoResolutionPipeline
{
    /** @param iterable<SeoResolver> $resolvers */
    public function __construct(private readonly iterable $resolvers) {}

    public function resolve(SeoDocument $document): SeoDocument
    {
        foreach ($this->resolvers as $resolver) {
            $document = $resolver->resolve($document);
        }

        return $document;
    }
}
