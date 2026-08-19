<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Data\IndexableData;
use Jenlut\YoastSeoLaravel\Indexables\StatelessIndexableRepository;

it('never reads or persists indexables in stateless mode', function () {
    $repository = new StatelessIndexableRepository;
    $data = new IndexableData('post', '1', null, hash('sha256', 'post:1'));

    expect($repository->findByIdentity('post', '1'))->toBeNull()
        ->and($repository->findByPermalinkHash($data->permalinkHash))->toBeNull()
        ->and($repository->save($data))->toBe($data);

    $repository->deleteByIdentity('post', '1');
    expect(true)->toBeTrue();
});
