<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Data\IndexableData;
use Jenlut\YoastSeoLaravel\Indexables\InMemoryIndexableRepository;

it('isolates indexed records by object identity', function () {
    $repository = new InMemoryIndexableRepository;
    $post = new IndexableData('post', '1', null, hash('sha256', 'post:1'));
    $page = new IndexableData('page', '1', null, hash('sha256', 'page:1'));

    $repository->save($post);
    $repository->save($page);

    expect($repository->findByIdentity('post', '1'))->toBe($post)
        ->and($repository->findByIdentity('page', '1'))->toBe($page)
        ->and($repository->findByIdentity('post', '2'))->toBeNull()
        ->and($repository->findByPermalinkHash($post->permalinkHash))->toBe($post);

    $repository->deleteByIdentity('post', '1');

    expect($repository->findByIdentity('post', '1'))->toBeNull()
        ->and($repository->findByIdentity('page', '1'))->toBe($page);
});

it('does not leak records between repositories', function () {
    $data = new IndexableData('post', '1', null, hash('sha256', 'post:1'));
    $first = new InMemoryIndexableRepository;
    $second = new InMemoryIndexableRepository;

    $first->save($data);

    expect($second->findByIdentity('post', '1'))->toBeNull();
});
