<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Contracts;

use Jenlut\YoastSeoLaravel\Data\ContentContext;

interface IndexableSource
{
    /** @return iterable<ContentContext> */
    public function contexts(?string $type = null): iterable;
}

// ponytail: sources enumerate content; commands never inspect arbitrary model classes.
