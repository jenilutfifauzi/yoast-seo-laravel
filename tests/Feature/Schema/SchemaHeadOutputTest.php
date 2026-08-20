<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\SeoManager;

it('renders explicit schema metadata in the head output', function () {
    $html = (new SeoManager)
        ->title('Article title')
        ->schema([
            '@type' => 'Article',
            '@id' => 'https://example.test/#article',
            'headline' => 'Article title',
        ])
        ->render();

    expect($html)
        ->toContain('<script type="application/ld+json">')
        ->toContain('"@type":"Article"');
});

it('does not render a JSON-LD block when schema is empty', function () {
    expect((new SeoManager)->title('Only title')->render())
        ->not->toContain('application/ld+json');
});
