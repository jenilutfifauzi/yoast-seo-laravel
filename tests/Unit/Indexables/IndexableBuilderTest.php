<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Indexables\DefaultIndexableBuilder;

it('builds normalized indexable data from generic content', function () {
    $data = (new DefaultIndexableBuilder)->build(new ContentContext(
        type: 'post',
        identifier: 42,
        url: 'https://example.test/posts/42',
        title: 'Post title',
        body: 'Post body',
    ));

    expect($data->objectType)->toBe('post')
        ->and($data->objectId)->toBe('42')
        ->and($data->permalink)->toBe('https://example.test/posts/42')
        ->and($data->title)->toBe('Post title')
        ->and($data->permalinkHash)->toBe(hash('sha256', $data->permalink))
        ->and($data->public)->toBeTrue();
});

it('produces the same permalink hash for the same identity', function () {
    $builder = new DefaultIndexableBuilder;
    $first = $builder->build(new ContentContext('post', 42, 'https://example.test/posts/42'));
    $second = $builder->build(new ContentContext('post', 42, 'https://example.test/posts/42'));

    expect($first)->toEqual($second);
});
