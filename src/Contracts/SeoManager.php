<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Contracts;

use Jenlut\YoastSeoLaravel\Data\SeoDocument;

interface SeoManager
{
    public function for(string|int|object|null $content): self;

    public function content(): string|int|object|null;

    public function title(?string $title): self;

    public function description(?string $description): self;

    public function canonical(?string $canonical): self;

    public function robots(?string $robots): self;

    /** @param array<string, mixed> $openGraph */
    public function openGraph(array $openGraph): self;

    /** @param array<string, mixed> $twitter */
    public function twitter(array $twitter): self;

    /** @param array<string, mixed> $schema */
    public function schema(array $schema): self;

    public function document(): SeoDocument;

    public function render(): SeoDocument;
}
