@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;gap:4px;">
    @if ($paginator->onFirstPage())
        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;color:#ccc;font-size:14px;cursor:not-allowed;">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;color:#33528A;font-size:14px;text-decoration:none;">‹</a>
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;color:#33528A;font-size:14px;text-decoration:none;">›</a>
    @else
        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;color:#ccc;font-size:14px;cursor:not-allowed;">›</span>
    @endif
</nav>
@endif