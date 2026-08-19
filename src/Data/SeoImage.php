<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Data;

use InvalidArgumentException;

final readonly class SeoImage
{
    private function __construct(public string $url) {}

    public static function fromString(string $url): self
    {
        $url = trim($url);
        $parts = parse_url($url);

        if ($url === '' || $parts === false || ! isset($parts['scheme'], $parts['host'])) {
            throw new InvalidArgumentException('SEO image URL must be an absolute URL.');
        }

        if (! in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            throw new InvalidArgumentException('SEO image URL must use HTTP or HTTPS.');
        }

        return new self($url);
    }
}
