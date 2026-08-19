<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Indexables;

use InvalidArgumentException;

enum IndexableMode: string
{
    case STATELESS = 'stateless';
    case INDEXED = 'indexed';

    public static function fromConfig(bool|string $value): self
    {
        if ($value === false) {
            return self::STATELESS;
        }

        if ($value === true) {
            return self::INDEXED;
        }

        return self::tryFrom($value) ?? throw new InvalidArgumentException("Unsupported indexable mode [{$value}].");
    }
}
