<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Data;

use InvalidArgumentException;

final readonly class RobotsDirective
{
    /** @var list<string> */
    private array $tokens;

    /** @param list<string> $tokens */
    public function __construct(array $tokens)
    {
        $normalized = array_values(array_unique(array_map('strtolower', $tokens)));

        foreach ($normalized as $token) {
            if (! in_array($token, self::allowed(), true)) {
                throw new InvalidArgumentException("Unsupported robots directive: {$token}");
            }
        }

        $this->tokens = $normalized;
    }

    public static function fromString(string $value): self
    {
        return new self(array_values(array_filter(
            array_map('trim', explode(',', $value)),
            static fn (string $token): bool => $token !== '',
        )));
    }

    /** @return list<string> */
    public function tokens(): array
    {
        return $this->tokens;
    }

    public function __toString(): string
    {
        return implode(',', $this->tokens);
    }

    /** @return list<string> */
    private static function allowed(): array
    {
        return [
            'all',
            'follow',
            'index',
            'indexifembedded',
            'max-image-preview:large',
            'max-snippet:-1',
            'max-video-preview:-1',
            'noarchive',
            'nofollow',
            'noimageindex',
            'noindex',
            'nosnippet',
            'notranslate',
            'noodp',
            'none',
            'unavailable_after',
        ];
    }
}
