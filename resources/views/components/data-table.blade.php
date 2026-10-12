@props([
    'items',
    'columns' => [],
    'title' => null,
    'subtitle' => null,
    'searchPlaceholder' => 'Search...',
    'filters' => [],
    'exportPdfRoute' => null,
    'exportExcelRoute' => null,
    'refreshRoute' => null,
    'createRoute' => null,
    'createLabel' => '+ Add New',
    'backRoute' => null,
    'backLabel' => '← Back',
    'emptyIcon' => '📭',
    'emptyMessage' => 'No records found',
    'emptyActionUrl' => null,
    'emptyActionLabel' => null,
    'perPageOptions' => [10, 25, 50, 100],
    'defaultPerPage' => 10,
    'rowActions' => [],
    'showSearch' => true,
])

<div style="display:flex;flex-direction:column;height:100%;gap:16px;">

    {{-- HEADER --}}
    @if($title || $createRoute || $exportPdfRoute || $exportExcelRoute || $backRoute)
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;flex-shrink:0;">
            <div>
                @if($title)
                    <h1 style="font-size:24px;font-weight:700;color:#1a1a1a;margin-bottom:4px;">{{ $title }}</h1>
                @endif
                @if($subtitle)
                    <p style="color:#6b7280;font-size:14px;">{{ $subtitle }}</p>
                @endif
            </div>

            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                @if($backRoute)
                    <a href="{{ $backRoute }}" style="display:flex;align-items:center;gap:6px;padding:11px 16px;background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;">{{ $backLabel }}</a>
                @endif
                @if($exportPdfRoute)
                    <a href="{{ $exportPdfRoute }}" title="PDF" style="display:flex;align-items:center;justify-content:center;width:42px;height:42px;background:#dc2626;color:white;border-radius:10px;text-decoration:none;font-size:18px;">📄</a>
                @endif
                @if($exportExcelRoute)
                    <a href="{{ $exportExcelRoute }}" title="Excel" style="display:flex;align-items:center;justify-content:center;width:42px;height:42px;background:#16a34a;color:white;border-radius:10px;text-decoration:none;font-size:18px;">📊</a>
                @endif
                @if($refreshRoute)
                    <a href="{{ $refreshRoute }}" title="Refresh" style="display:flex;align-items:center;justify-content:center;width:42px;height:42px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;border-radius:10px;text-decoration:none;font-size:16px;">🔄</a>
                @endif
                @if($createRoute)
                    <a href="{{ $createRoute }}" style="display:flex;align-items:center;gap:8px;padding:11px 20px;background:#ff8a3d;color:white;border-radius:10px;font-size:13.5px;font-weight:600;text-decoration:none;box-shadow:0 2px 6px rgba(255,138,61,0.3);">{{ $createLabel }}</a>
                @endif
            </div>
        </div>
    @endif

    {{-- FILTERS --}}
    @if($showSearch || count($filters) > 0)
        <div style="background:white;border-radius:14px;padding:16px 20px;box-shadow:0 1px 3px rgba(0,0,0,0.04);flex-shrink:0;">
            <form method="GET" class="dt-filter-form" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                <input type="hidden" name="per_page" value="{{ request('per_page', $defaultPerPage) }}">

                @if($showSearch)
                    <div style="flex:1;min-width:180px;max-width:320px;position:relative;">
                        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#9ca3af;">🔍</span>
                        <input name="search" value="{{ request('search') }}" placeholder="{{ $searchPlaceholder }}"
                               class="dt-search-input"
                               style="width:100%;padding:11px 14px 11px 40px;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;background:#fafafa;outline:none;font-family:inherit;">
                    </div>
                @endif

                @foreach($filters as $filter)
                    @if(($filter['type'] ?? 'select') === 'select')
                        <select name="{{ $filter['name'] }}" style="padding:11px 14px;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;background:#fafafa;font-family:inherit;color:#374151;">
                            <option value="">{{ $filter['placeholder'] ?? 'All' }}</option>
                            @foreach($filter['options'] as $value => $label)
                                <option value="{{ $value }}" {{ request($filter['name']) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    @elseif(($filter['type'] ?? '') === 'date')
                        <input name="{{ $filter['name'] }}" type="date" value="{{ request($filter['name']) }}"
                               style="padding:11px 14px;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;background:#fafafa;font-family:inherit;color:#374151;">
                    @endif
                @endforeach

                @if(request()->hasAny(array_merge(['search'], array_column($filters, 'name'))))
                    <a href="{{ url()->current() }}" style="padding:11px 16px;color:#dc2626;font-size:13px;text-decoration:none;font-weight:600;background:#fef2f2;border-radius:10px;">✕ Clear</a>
                @endif
            </form>
        </div>
    @endif

    {{-- TABLE --}}
    <div style="background:white;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.04);flex:1 1 auto;min-height:0;display:flex;flex-direction:column;">

        <div style="flex:1 1 auto;min-height:0;overflow-y:auto;overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13.5px;">
                <thead style="position:sticky;top:0;z-index:2;">
                    <tr style="background:#f9fafb;">
                        @foreach($columns as $col)
                            @php
                                $isString = is_string($col);
                                $label = $isString ? \Illuminate\Support\Str::headline($col) : ($col['label'] ?? '');
                                $align = $isString ? 'left' : ($col['align'] ?? 'left');
                            @endphp
                            <th style="padding:14px 18px;text-align:{{ $align }};font-weight:600;color:#6b7280;font-size:11.5px;text-transform:uppercase;letter-spacing:0.6px;background:#f9fafb;">
                                {{ $label }}
                            </th>
                        @endforeach

                        @if(count($rowActions) > 0)
                            <th style="padding:14px 18px;text-align:right;background:#f9fafb;"></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr style="border-bottom:1px solid #f3f4f6;">
                            @foreach($columns as $col)
                                @php
                                    $isString = is_string($col);
                                    $field = $isString ? $col : ($col['field'] ?? null);
                                    $type = $isString ? 'text' : ($col['type'] ?? 'text');
                                    $align = $isString ? 'left' : ($col['align'] ?? 'left');
                                    $value = $field ? data_get($item, $field) : null;
                                @endphp
                                <td style="padding:16px 18px;text-align:{{ $align }};">
                                    {!! \App\Helpers\TableRenderer::render($item, $field, $value, $type, $isString ? [] : $col) !!}
                                </td>
                            @endforeach

                            @if(count($rowActions) > 0)
                                <td style="padding:16px 18px;text-align:right;">
                                    <div style="display:flex;justify-content:flex-end;gap:6px;">
                                        @foreach($rowActions as $action)
                                            @if(isset($action['render']))
                                                {!! $action['render']($item) !!}
                                            @elseif(isset($action['route']))
                                                @php
                                                    $url = is_callable($action['route']) ? $action['route']($item) : route($action['route'], $item);
                                                    $icon = $action['icon'] ?? '👁️';
                                                @endphp
                                                <a href="{{ $url }}" title="{{ $action['label'] ?? 'View' }}"
                                                   style="display:inline-flex;width:34px;height:34px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;align-items:center;justify-content:center;text-decoration:none;font-size:14px;">
                                                    {{ $icon }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + (count($rowActions) > 0 ? 1 : 0) }}" style="padding:60px 20px;text-align:center;color:#9ca3af;">
                                <div style="font-size:52px;margin-bottom:12px;">{{ $emptyIcon }}</div>
                                <div style="font-size:16px;font-weight:600;color:#6b7280;margin-bottom:6px;">{{ $emptyMessage }}</div>
                                @if($emptyActionUrl)
                                    <a href="{{ $emptyActionUrl }}" style="display:inline-block;margin-top:12px;padding:10px 20px;background:#ff8a3d;color:white;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;">{{ $emptyActionLabel ?? 'Add New' }}</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if(method_exists($items, 'total') && $items->total() > 0)
            <div style="padding:16px 20px;border-top:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;background:#fafafa;flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <label style="font-size:13px;color:#6b7280;">Rows per page:</label>
                        <select class="dt-per-page" style="padding:7px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;background:white;font-family:inherit;color:#374151;cursor:pointer;font-weight:600;">
                            @foreach($perPageOptions as $opt)
                                <option value="{{ $opt }}" {{ request('per_page', $defaultPerPage) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="font-size:13px;color:#6b7280;">
                        Showing <strong style="color:#1a1a1a;">{{ $items->firstItem() }}</strong>–<strong style="color:#1a1a1a;">{{ $items->lastItem() }}</strong> of <strong style="color:#1a1a1a;">{{ $items->total() }}</strong>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:6px;">
                    @if($items->onFirstPage())
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#f3f4f6;color:#d1d5db;border-radius:8px;font-size:14px;cursor:not-allowed;">‹</span>
                    @else
                        <a href="{{ $items->previousPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:white;color:#6b7280;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;text-decoration:none;">‹</a>
                    @endif

                    @php
                        $cur = $items->currentPage();
                        $last = $items->lastPage();
                        $s = max(1, $cur - 2);
                        $e = min($last, $cur + 2);
                    @endphp

                    @if($s > 1)
                        <a href="{{ $items->url(1) }}" style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 10px;background:white;color:#6b7280;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">1</a>
                        @if($s > 2)<span style="color:#9ca3af;">…</span>@endif
                    @endif

                    @for($i = $s; $i <= $e; $i++)
                        @if($i == $cur)
                            <span style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 10px;background:#ff8a3d;color:white;border-radius:8px;font-size:13px;font-weight:700;">{{ $i }}</span>
                        @else
                            <a href="{{ $items->url($i) }}" style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 10px;background:white;color:#6b7280;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">{{ $i }}</a>
                        @endif
                    @endfor

                    @if($e < $last)
                        @if($e < $last - 1)<span style="color:#9ca3af;">…</span>@endif
                        <a href="{{ $items->url($last) }}" style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 10px;background:white;color:#6b7280;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">{{ $last }}</a>
                    @endif

                    @if($items->hasMorePages())
                        <a href="{{ $items->nextPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:white;color:#6b7280;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;text-decoration:none;">›</a>
                    @else
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#f3f4f6;color:#d1d5db;border-radius:8px;font-size:14px;cursor:not-allowed;">›</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.dt-per-page').forEach(function(sel) {
        sel.addEventListener('change', function() {
            var url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    });

    var form = document.querySelector('.dt-filter-form');
    if (form) {
        form.querySelectorAll('select, input[type="date"]').forEach(function(el) {
            el.addEventListener('change', function() { form.submit(); });
        });

        var searchInput = form.querySelector('.dt-search-input');
        if (searchInput) {
            var timer = null;
            searchInput.addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(function() { form.submit(); }, 500);
            });
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); clearTimeout(timer); form.submit(); }
            });
        }
    }
});
</script>
@endpush
@endonce