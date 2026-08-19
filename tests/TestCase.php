<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Tests;

use Jenlut\YoastSeoLaravel\YoastSeoLaravelServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            YoastSeoLaravelServiceProvider::class,
        ];
    }
}
