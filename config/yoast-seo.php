<?php

declare(strict_types=1);

return [
    'enabled' => true,

    'title' => [
        'separator' => ' | ',
        'default' => null,
        'suffix' => null,
    ],

    'description' => [
        'default' => null,
        'max_length' => 160,
    ],

    'canonical' => [
        'enabled' => true,
        'force_https' => false,
    ],

    'robots' => [
        'default' => 'index,follow',
    ],

    'open_graph' => [
        'enabled' => true,
        'site_name' => null,
        'default_image' => null,
    ],

    'twitter' => [
        'enabled' => true,
        'card' => 'summary_large_image',
        'site' => null,
        'creator' => null,
    ],

    'schema' => [
        'enabled' => true,
    ],

    'indexables' => [
        'enabled' => false,
        'table' => 'yoast_seo_indexables',
        'queue' => false,
    ],

    'sitemap' => [
        'enabled' => false,
        'path' => 'sitemap.xml',
        'cache_seconds' => 3600,
    ],

    'analysis' => [
        'enabled' => true,
        'expose_keyphrase_publicly' => false,
    ],

    'cache' => [
        'enabled' => true,
        'store' => null,
        'prefix' => 'yoast-seo',
    ],

    'failure_policy' => [
        'metadata' => 'fallback',
        'schema' => 'skip_failed_piece',
        'sitemap' => 'fail_request',
        'analysis' => 'return_empty_result',
    ],
];
