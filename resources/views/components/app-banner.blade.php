@props(['title'])

<div {{ $attributes->merge(['class' => 'app-page-banner']) }}>
    <div class="app-page-banner-inner">
        <div class="app-page-banner-text">
            <h1 class="app-page-banner-title">{{ $title }}</h1>
            @isset($subtitle)
                @if($subtitle->isNotEmpty())
                    <div class="app-page-banner-sub">{{ $subtitle }}</div>
                @endif
            @endisset
        </div>
        @isset($actions)
            @if($actions->isNotEmpty())
                <div class="app-page-banner-actions">{{ $actions }}</div>
            @endif
        @endisset
    </div>
</div>
