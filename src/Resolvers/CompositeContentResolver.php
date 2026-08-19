<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Resolvers;

use Jenlut\YoastSeoLaravel\Contracts\ContentResolver;
use Jenlut\YoastSeoLaravel\Data\ContentContext;

final readonly class CompositeContentResolver implements ContentResolver
{
    /** @param list<ContentResolver> $resolvers */
    public function __construct(private array $resolvers) {}

    public function resolve(mixed $source): ?ContentContext
    {
        foreach ($this->resolvers as $resolver) {
            $context = $resolver->resolve($source);

            if ($context !== null) {
                return $context;
            }
        }

        return null;
    }
}

// ponytail: route resolution is intentionally opt-in; applications own model mapping.
