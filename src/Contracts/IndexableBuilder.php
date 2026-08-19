<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Contracts;

use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\IndexableData;

interface IndexableBuilder
{
    public function build(ContentContext $context): IndexableData;
}
