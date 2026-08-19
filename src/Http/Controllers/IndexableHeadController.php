<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Jenlut\YoastSeoLaravel\Data\SeoDocument;
use Jenlut\YoastSeoLaravel\Http\Requests\ResolveSeoRequest;
use Jenlut\YoastSeoLaravel\Pipeline\SeoResolutionPipeline;
use Jenlut\YoastSeoLaravel\Presenters\SeoHeadPresenter;
use Jenlut\YoastSeoLaravel\Resolvers\DefaultSeoResolver;
use Jenlut\YoastSeoLaravel\Resolvers\RequestSeoResolver;

final class IndexableHeadController
{
    public function __invoke(ResolveSeoRequest $request): JsonResponse
    {
        $document = (new SeoResolutionPipeline([
            new RequestSeoResolver($request->validated()),
            new DefaultSeoResolver,
        ]))->resolve(SeoDocument::empty());

        return response()->json([
            'data' => [
                'title' => $document->title,
                'description' => $document->description,
                'canonical' => $document->canonical?->value,
                'robots' => $document->robots ? (string) $document->robots : null,
                'open_graph' => $document->openGraph,
                'twitter' => $document->twitter,
                'html' => (new SeoHeadPresenter)->present($document),
            ],
        ]);
    }
}
