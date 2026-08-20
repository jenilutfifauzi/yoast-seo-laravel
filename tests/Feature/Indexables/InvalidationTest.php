<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Jenlut\YoastSeoLaravel\Contracts\IndexableRepository;
use Jenlut\YoastSeoLaravel\Data\IndexableData;
use Jenlut\YoastSeoLaravel\Events\IndexableInvalidated;
use Jenlut\YoastSeoLaravel\Indexables\InMemoryIndexableRepository;

it('deletes an identity and dispatches invalidation', function () {
    Event::fake();
    $repository = new InMemoryIndexableRepository;
    $repository->save(new IndexableData('post', '1', null, 'hash-1', 'Title'));
    $this->app->instance(IndexableRepository::class, $repository);

    $this->artisan('yoast-seo:invalidate', ['--type' => 'post', '--id' => '1'])
        ->assertSuccessful();

    expect($repository->findByIdentity('post', '1'))->toBeNull();
    Event::assertDispatched(IndexableInvalidated::class);
});

it('requires both invalidation identity options', function () {
    $this->artisan('yoast-seo:invalidate', ['--type' => 'post'])
        ->assertExitCode(2)
        ->expectsOutput('Both --type and --id are required.');
});

it('rejects empty invalidation identity values', function () {
    $this->artisan('yoast-seo:invalidate', ['--type' => '', '--id' => '1'])
        ->assertExitCode(2);
});
