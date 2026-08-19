<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Jenlut\YoastSeoLaravel\Http\Controllers\IndexableHeadController;

Route::get('yoast-seo/head', IndexableHeadController::class)
    ->middleware('throttle:seo')
    ->name('yoast-seo.head');
