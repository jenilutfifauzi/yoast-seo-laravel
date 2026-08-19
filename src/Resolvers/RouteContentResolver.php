<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Resolvers;

use Illuminate\Http\Request;
use Jenlut\YoastSeoLaravel\Contracts\ContentResolver;
use Jenlut\YoastSeoLaravel\Data\ContentContext;

final class RouteContentResolver implements ContentResolver
{
    public function resolve(mixed $source): ?ContentContext
    {
        if (! $source instanceof Request) {
            return null;
        }

        $route = $source->route();
        $name = $route?->getName();

        if ($name === null) {
            return null;
        }

        return new ContentContext(
            type: 'route',
            identifier: $name,
            url: $source->fullUrl(),
            title: $source->get('title'),
            body: $source->get('body'),
        );
    }
}
