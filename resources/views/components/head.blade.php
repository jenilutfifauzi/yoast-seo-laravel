@php
    $document ??= app(\Jenlut\YoastSeoLaravel\SeoManager::class)->document();
@endphp
{!! (new \Jenlut\YoastSeoLaravel\Presenters\SeoHeadPresenter)->present($document) !!}

