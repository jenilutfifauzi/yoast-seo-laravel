<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Models;

use Illuminate\Database\Eloquent\Model;

final class SeoLink extends Model
{
    protected $table = 'yoast_seo_links';

    protected $guarded = [];
}
