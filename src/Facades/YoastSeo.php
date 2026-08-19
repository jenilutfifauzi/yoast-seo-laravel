<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use Jenlut\YoastSeoLaravel\YoastSeoLaravel;

/**
 * @see YoastSeoLaravel
 */
class YoastSeo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return YoastSeoLaravel::class;
    }
}
