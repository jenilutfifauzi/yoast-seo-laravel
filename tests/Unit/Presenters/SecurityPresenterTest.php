<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Data\CanonicalUrl;
use Jenlut\YoastSeoLaravel\Data\RobotsDirective;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Presenters\SeoHeadPresenter;

it('escapes title and description text', function () {
    $head = (new SeoHeadPresenter)->present(new SeoDocument(
        title: '<script>alert("x")</script>',
        description: 'A "quoted" description & more',
    ));

    expect($head)
        ->toContain('<title>&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt;</title>')
        ->toContain('content="A &quot;quoted&quot; description &amp; more"')
        ->not->toContain('<script>')
        ->not->toContain('"quoted"');
});

it('escapes canonical, robots, Open Graph, and Twitter attributes', function () {
    $head = (new SeoHeadPresenter)->present(new SeoDocument(
        canonical: CanonicalUrl::fromString('https://example.test/a?x=1&y=2'),
        robots: RobotsDirective::fromString('index,follow'),
        openGraph: [
            'title' => 'OG & title',
            'description' => 'OG "description"',
            'url' => 'https://example.test/og?a=1&b=2',
            'image' => 'https://example.test/image.jpg',
        ],
        twitter: [
            'card' => 'summary_large_image',
            'title' => 'Twitter <title>',
            'description' => 'Twitter description',
            'image' => 'https://example.test/twitter.jpg',
        ],
    ));

    expect($head)
        ->toContain('href="https://example.test/a?x=1&amp;y=2"')
        ->toContain('content="OG &amp; title"')
        ->toContain('content="OG &quot;description&quot;"')
        ->toContain('content="Twitter &lt;title&gt;"')
        ->not->toContain('javascript:')
        ->not->toContain('<title>Twitter');
});
