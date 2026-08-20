<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Jenlut\YoastSeoLaravel\Contracts\IndexableRepository;
use Jenlut\YoastSeoLaravel\Contracts\IndexableSource;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Data\IndexableData;
use Jenlut\YoastSeoLaravel\Events\IndexableUpdated;
use Jenlut\YoastSeoLaravel\Indexables\InMemoryIndexableRepository;

it('exposes the index command help', function () {
    $this->artisan('yoast-seo:index --help')->assertSuccessful();
});

it('indexes contexts from tagged sources and dispatches updates', function () {
    Event::fake();
    $repository = new InMemoryIndexableRepository;
    $this->app->instance(IndexableRepository::class, $repository);
    $source = new class implements IndexableSource
    {
        public ?string $receivedType = null;

        public function contexts(?string $type = null): iterable
        {
            $this->receivedType = $type;

            yield new ContentContext('post', '1', 'https://example.test/posts/1', 'Title', 'Description');
        }
    };
    $this->app->instance(IndexableSource::class, $source);
    $this->app->tag(IndexableSource::class, 'yoast-seo.indexable-sources');

    $this->artisan('yoast-seo:index', ['--type' => 'post'])->assertSuccessful();

    expect($source->receivedType)->toBe('post')
        ->and($repository->findByIdentity('post', '1'))->toBeInstanceOf(IndexableData::class);
    Event::assertDispatched(IndexableUpdated::class);
});

it('does not scan models when no source is tagged', function () {
    $this->artisan('yoast-seo:index')->assertSuccessful()
        ->expectsOutput('No indexable sources are registered.');
});
