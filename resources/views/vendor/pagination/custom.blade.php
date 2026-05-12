@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
    <div style="font-size:.8rem;color:var(--text-muted);">
        Showing <strong style="color:var(--text);">{{ $paginator->firstItem() }}</strong>
        – <strong style="color:var(--text);">{{ $paginator->lastItem() }}</strong>
        of <strong style="color:var(--text);">{{ $paginator->total() }}</strong> results
    </div>
    <div style="display:flex;gap:.3rem;align-items:center;">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span style="padding:.4rem .8rem;border-radius:6px;background:#1e293b;color:#475569;font-size:.8rem;cursor:not-allowed;">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               style="padding:.4rem .8rem;border-radius:6px;background:#334155;color:#f1f5f9;font-size:.8rem;text-decoration:none;transition:background .2s;"
               onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#334155'">‹</a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="padding:.4rem .6rem;color:#475569;font-size:.8rem;">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding:.4rem .8rem;border-radius:6px;background:#6366f1;color:#fff;font-size:.8rem;font-weight:700;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           style="padding:.4rem .8rem;border-radius:6px;background:#334155;color:#f1f5f9;font-size:.8rem;text-decoration:none;transition:background .2s;"
                           onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#334155'">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               style="padding:.4rem .8rem;border-radius:6px;background:#334155;color:#f1f5f9;font-size:.8rem;text-decoration:none;transition:background .2s;"
               onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#334155'">›</a>
        @else
            <span style="padding:.4rem .8rem;border-radius:6px;background:#1e293b;color:#475569;font-size:.8rem;cursor:not-allowed;">›</span>
        @endif
    </div>
</nav>
@endif
