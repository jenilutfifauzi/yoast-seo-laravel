<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jenlut\YoastSeoLaravel\Data\CanonicalUrl;
use Jenlut\YoastSeoLaravel\Data\IndexableData;
use Jenlut\YoastSeoLaravel\Data\RobotsDirective;
use Jenlut\YoastSeoLaravel\Indexables\EloquentIndexableRepository;
use Jenlut\YoastSeoLaravel\Models\Indexable;

beforeEach(function (): void {
    if (Schema::hasTable('yoast_seo_indexables')) {
        Schema::drop('yoast_seo_indexables');
    }

    Schema::create('yoast_seo_indexables', function (Blueprint $table): void {
        $table->id();
        $table->string('object_type', 100);
        $table->string('object_id', 191);
        $table->string('permalink_hash', 64);
        $table->text('permalink')->nullable();
        $table->string('title')->nullable();
        $table->text('description')->nullable();
        $table->text('canonical')->nullable();
        $table->string('robots', 500)->nullable();
        $table->boolean('public')->default(true);
        $table->json('open_graph')->nullable();
        $table->json('twitter')->nullable();
        $table->json('schema')->nullable();
        $table->unsignedInteger('version')->default(1);
        $table->timestamp('indexed_at')->nullable();
        $table->timestamps();
        $table->unique(['object_type', 'object_id']);
        $table->unique('permalink_hash');
    });
});

afterEach(function (): void {
    Schema::dropIfExists('yoast_seo_indexables');
});

it('round trips indexable data through the eloquent repository', function () {
    $repository = new EloquentIndexableRepository(new Indexable);
    $data = new IndexableData(
        objectType: 'post',
        objectId: '1',
        permalink: 'https://example.test/posts/1',
        permalinkHash: hash('sha256', 'https://example.test/posts/1'),
        title: 'Indexed title',
        description: 'Indexed description',
        canonical: CanonicalUrl::fromString('https://example.test/posts/1'),
        robots: RobotsDirective::fromString('index,follow'),
        openGraph: ['title' => 'Indexed title'],
        twitter: ['card' => 'summary'],
        schema: [['@type' => 'Article']],
    );

    $saved = $repository->save($data);

    expect($saved->title)->toBe('Indexed title')
        ->and($repository->findByIdentity('post', '1')->description)->toBe('Indexed description')
        ->and($repository->findByPermalinkHash($data->permalinkHash)->objectId)->toBe('1');
});

it('updates an identity instead of creating a duplicate row', function () {
    $repository = new EloquentIndexableRepository(new Indexable);
    $base = new IndexableData('post', '1', 'https://example.test/posts/1', 'hash-1', 'Old title');
    $updated = new IndexableData('post', '1', 'https://example.test/posts/1', 'hash-1', 'New title');

    $repository->save($base);
    $repository->save($updated);

    expect(Indexable::query()->count())->toBe(1)
        ->and($repository->findByIdentity('post', '1')->title)->toBe('New title');
});

it('deletes an indexable by its scoped identity', function () {
    $repository = new EloquentIndexableRepository(new Indexable);
    $repository->save(new IndexableData('post', '1', null, 'hash-1', 'Title'));

    $repository->deleteByIdentity('post', '1');

    expect($repository->findByIdentity('post', '1'))->toBeNull();
});

it('does not look up another object type with the same object id', function () {
    $repository = new EloquentIndexableRepository(new Indexable);
    $repository->save(new IndexableData('post', '1', null, 'post-hash', 'Post'));
    $repository->save(new IndexableData('page', '1', null, 'page-hash', 'Page'));

    expect($repository->findByIdentity('post', '1')->title)->toBe('Post')
        ->and($repository->findByIdentity('page', '1')->title)->toBe('Page');
});
