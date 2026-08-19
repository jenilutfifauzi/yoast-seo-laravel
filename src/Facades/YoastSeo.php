<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use Jenlut\YoastSeoLaravel\SeoManager;

/**
 * @see SeoManager
 */
class YoastSeo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SeoManager::class;
    }
}
