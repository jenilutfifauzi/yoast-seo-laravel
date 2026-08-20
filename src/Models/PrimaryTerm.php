<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Models;

use Illuminate\Database\Eloquent\Model;

final class PrimaryTerm extends Model
{
    protected $table = 'yoast_seo_primary_terms';

    protected $guarded = [];
}
