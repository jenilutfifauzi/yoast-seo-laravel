<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Data;

use InvalidArgumentException;

final readonly class CanonicalUrl
{
    private function __construct(public string $value) {}

    public static function fromString(string $value): self
    {
        $value = trim($value);
        $parts = parse_url($value);

        if ($value === '' || $parts === false || ! isset($parts['scheme'], $parts['host'])) {
            throw new InvalidArgumentException('Canonical URL must be an absolute URL.');
        }

        if (! in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            throw new InvalidArgumentException('Canonical URL must use HTTP or HTTPS.');
        }

        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
