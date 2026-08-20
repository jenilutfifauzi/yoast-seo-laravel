<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Models;

use Illuminate\Database\Eloquent\Model;

final class Indexable extends Model
{
    protected $table = 'yoast_seo_indexables';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'public' => 'boolean',
            'open_graph' => 'array',
            'twitter' => 'array',
            'schema' => 'array',
            'indexed_at' => 'datetime',
        ];
    }
}
