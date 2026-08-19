<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

it('publishes and runs the normalized indexable migration', function () {
    $this->artisan('vendor:publish', [
        '--tag' => 'yoast-seo-migrations',
        '--force' => true,
    ])->assertSuccessful();

    $this->artisan('migrate')->assertSuccessful();

    expect(Schema::hasTable('yoast_seo_indexables'))->toBeTrue()
        ->and(Schema::hasColumns('yoast_seo_indexables', [
            'object_type',
            'object_id',
            'permalink_hash',
            'title',
            'description',
            'open_graph',
            'twitter',
            'schema',
        ]))->toBeTrue();
});

it('enforces one indexed row per object identity', function () {
    $this->artisan('migrate')->assertSuccessful();

    $this->expectException(QueryException::class);

    DB::table('yoast_seo_indexables')->insert([
        'object_type' => 'post',
        'object_id' => '1',
        'permalink_hash' => hash('sha256', 'post:1'),
        'public' => true,
    ]);

    DB::table('yoast_seo_indexables')->insert([
        'object_type' => 'post',
        'object_id' => '1',
        'permalink_hash' => hash('sha256', 'post:1:duplicate'),
        'public' => true,
    ]);
});
