<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Contracts;

use Jenlut\YoastSeoLaravel\Data\ContentContext;

interface ContentResolver
{
    public function resolve(mixed $source): ?ContentContext;
}
