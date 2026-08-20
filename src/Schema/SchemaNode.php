<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Schema;

use Closure;

final readonly class SchemaNode
{
    /** @param array<string, mixed> $properties */
    public function __construct(private array $properties)
    {
        $type = $properties['@type'] ?? null;

        if (! is_string($type) || trim($type) === '') {
            throw new SchemaValidationException('Schema node requires a non-empty @type.');
        }

        if (isset($properties['@id']) && ! self::validId($properties['@id'])) {
            throw new SchemaValidationException('Schema @id must be an HTTP(S) URL or local identifier.');
        }

        self::validateValues($properties);
    }

    public function type(): string
    {
        return $this->properties['@type'];
    }

    public function id(): ?string
    {
        return $this->properties['@id'] ?? null;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return $this->properties;
    }

    private static function validId(mixed $id): bool
    {
        if (! is_string($id) || $id === '') {
            return false;
        }

        return str_starts_with($id, '#')
            || filter_var($id, FILTER_VALIDATE_URL) !== false
                && in_array(parse_url($id, PHP_URL_SCHEME), ['http', 'https'], true);
    }

    private static function validateValues(mixed $value): void
    {
        if ($value instanceof Closure || is_object($value) || is_resource($value)) {
            throw new SchemaValidationException('Schema properties must not contain executable or object values.');
        }

        if (is_array($value)) {
            foreach ($value as $nested) {
                self::validateValues($nested);
            }
        }
    }
}
