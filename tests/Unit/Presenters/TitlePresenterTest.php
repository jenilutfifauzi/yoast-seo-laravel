<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Presenters\SeoHeadPresenter;
use Jenlut\YoastSeoLaravel\Presenters\TitlePresenter;

it('renders a safe title and metadata head', function () {
    $document = new SeoDocument(
        title: 'SEO title',
        description: 'A description',
    );

    expect((new TitlePresenter)->present($document))->toBe('<title>SEO title</title>')
        ->and((new SeoHeadPresenter)->present($document))->toContain('<title>SEO title</title>')
        ->and((new SeoHeadPresenter)->present($document))->toContain('<meta name="description" content="A description">');
});

it('omits empty metadata instead of emitting blank tags', function () {
    $head = (new SeoHeadPresenter)->present(SeoDocument::empty());

    expect($head)->toBe('');
});
