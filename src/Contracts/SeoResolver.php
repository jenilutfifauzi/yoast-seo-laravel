<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Contracts;

use Jenlut\YoastSeoLaravel\Data\SeoDocument;

interface SeoResolver
{
    public function resolve(SeoDocument $document): SeoDocument;
}
