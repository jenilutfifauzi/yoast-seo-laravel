<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Presenters;

use Jenlut\YoastSeoLaravel\Data\SeoDocument;

final class SeoHeadPresenter
{
    public function present(SeoDocument $document): string
    {
        $tags = [];

        if ($document->title !== null) {
            $tags[] = (new TitlePresenter)->present($document);
        }

        if ($document->description !== null) {
            $tags[] = $this->meta('name', 'description', $document->description);
        }

        if ($document->canonical !== null) {
            $tags[] = '<link rel="canonical" href="'.TitlePresenter::escape($document->canonical->value).'">';
        }

        if ($document->robots !== null) {
            $tags[] = $this->meta('name', 'robots', (string) $document->robots);
        }

        if ($document->schema !== []) {
            $schema = isset($document->schema[0])
                ? $document->schema
                : [$document->schema];
            $tags[] = (new SchemaPresenter)->present($schema);
        }

        foreach ([
            'og:title' => $document->openGraph['title'] ?? null,
            'og:description' => $document->openGraph['description'] ?? null,
            'og:url' => $document->openGraph['url'] ?? null,
            'og:image' => $document->openGraph['image'] ?? null,
        ] as $property => $value) {
            if (is_string($value) && $value !== '') {
                $tags[] = $this->meta('property', $property, $value);
            }
        }

        foreach ([
            'twitter:card' => $document->twitter['card'] ?? null,
            'twitter:title' => $document->twitter['title'] ?? null,
            'twitter:description' => $document->twitter['description'] ?? null,
            'twitter:image' => $document->twitter['image'] ?? null,
        ] as $name => $value) {
            if (is_string($value) && $value !== '') {
                $tags[] = $this->meta('name', $name, $value);
            }
        }

        return implode("\n", $tags);
    }

    private function meta(string $attribute, string $name, string $value): string
    {
        return '<meta '.$attribute.'="'.TitlePresenter::escape($name).'" content="'.TitlePresenter::escape($value).'">';
    }
}
