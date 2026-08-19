<?php

declare(strict_types=1);

namespace YoastSeoLaravel\YoastSeoLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \YoastSeoLaravel\YoastSeoLaravel\YoastSeoLaravel
 */
class YoastSeoLaravel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \YoastSeoLaravel\YoastSeoLaravel\YoastSeoLaravel::class;
    }
}
