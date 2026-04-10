@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;gap:4px;">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;color:#ccc;font-size:12px;">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;color:var(--primary);font-size:12px;text-decoration:none;">‹</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;font-size:12px;color:#999;">...</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:var(--primary);color:white;font-size:12px;font-weight:600;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;color:var(--primary);font-size:12px;text-decoration:none;font-weight:600;">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;color:var(--primary);font-size:12px;text-decoration:none;">›</a>
    @else
        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;color:#ccc;font-size:12px;">›</span>
    @endif
</nav>
@endif