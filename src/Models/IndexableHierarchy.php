<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Models;

use Illuminate\Database\Eloquent\Model;

final class IndexableHierarchy extends Model
{
    protected $table = 'yoast_seo_indexable_hierarchy';

    protected $guarded = [];
}
