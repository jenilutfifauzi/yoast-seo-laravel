<?php

declare(strict_types=1);

use Jenlut\YoastSeoLaravel\Contracts\ContentResolver;
use Jenlut\YoastSeoLaravel\Data\ContentContext;
use Jenlut\YoastSeoLaravel\Resolvers\CompositeContentResolver;

final class FakeContentResolver implements ContentResolver
{
    public function __construct(private readonly ?ContentContext $context) {}

    public function resolve(mixed $source): ?ContentContext
    {
        return $this->context;
    }
}

it('returns the first matching context from a composite resolver', function () {
    $expected = new ContentContext(type: 'page', identifier: 'home');
    $resolver = new CompositeContentResolver([
        new FakeContentResolver(null),
        new FakeContentResolver($expected),
    ]);

    expect($resolver->resolve(['route' => 'home']))->toBe($expected);
});

it('returns null when no resolver supports the source', function () {
    expect((new CompositeContentResolver([
        new FakeContentResolver(null),
    ]))->resolve(new stdClass))->toBeNull();
});
