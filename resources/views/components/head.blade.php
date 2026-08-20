@php
    $manager = app(\Jenlut\YoastSeoLaravel\SeoManager::class);
    $html = isset($document)
        ? (new \Jenlut\YoastSeoLaravel\Presenters\SeoHeadPresenter)->present($document)
        : (isset($content)
            ? $manager->for($content)->render()
            : (new \Jenlut\YoastSeoLaravel\Presenters\SeoHeadPresenter)->present($manager->document()));
@endphp
{!! $html !!}

