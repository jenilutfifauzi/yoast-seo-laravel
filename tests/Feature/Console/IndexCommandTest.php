<?php

declare(strict_types=1);

it('exposes safe index and invalidation commands', function () {
    $this->artisan('yoast-seo:index --help')->assertSuccessful();
    $this->artisan('yoast-seo:index', ['--type' => 'post'])->assertSuccessful();
    $this->artisan('yoast-seo:invalidate', ['--type' => 'post', '--id' => '1'])->assertSuccessful();
});

it('requires an identity for invalidation', function () {
    $this->artisan('yoast-seo:invalidate')->assertExitCode(2);
});
