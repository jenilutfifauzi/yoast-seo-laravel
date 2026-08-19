<?php

declare(strict_types=1);

namespace YoastSeoLaravel\YoastSeoLaravel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use YoastSeoLaravel\YoastSeoLaravel\YoastSeoLaravelServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            YoastSeoLaravelServiceProvider::class,
        ];
    }
}
