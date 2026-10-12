<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
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
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
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
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div style="display:flex;flex-direction:column;height:100%;gap:16px;">

    
    <?php if($title || $createRoute || $exportPdfRoute || $exportExcelRoute || $backRoute): ?>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;flex-shrink:0;">
            <div>
                <?php if($title): ?>
                    <h1 style="font-size:24px;font-weight:700;color:#1a1a1a;margin-bottom:4px;"><?php echo e($title); ?></h1>
                <?php endif; ?>
                <?php if($subtitle): ?>
                    <p style="color:#6b7280;font-size:14px;"><?php echo e($subtitle); ?></p>
                <?php endif; ?>
            </div>

            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <?php if($backRoute): ?>
                    <a href="<?php echo e($backRoute); ?>" style="display:flex;align-items:center;gap:6px;padding:11px 16px;background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;"><?php echo e($backLabel); ?></a>
                <?php endif; ?>
                <?php if($exportPdfRoute): ?>
                    <a href="<?php echo e($exportPdfRoute); ?>" title="PDF" style="display:flex;align-items:center;justify-content:center;width:42px;height:42px;background:#dc2626;color:white;border-radius:10px;text-decoration:none;font-size:18px;">📄</a>
                <?php endif; ?>
                <?php if($exportExcelRoute): ?>
                    <a href="<?php echo e($exportExcelRoute); ?>" title="Excel" style="display:flex;align-items:center;justify-content:center;width:42px;height:42px;background:#16a34a;color:white;border-radius:10px;text-decoration:none;font-size:18px;">📊</a>
                <?php endif; ?>
                <?php if($refreshRoute): ?>
                    <a href="<?php echo e($refreshRoute); ?>" title="Refresh" style="display:flex;align-items:center;justify-content:center;width:42px;height:42px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;border-radius:10px;text-decoration:none;font-size:16px;">🔄</a>
                <?php endif; ?>
                <?php if($createRoute): ?>
                    <a href="<?php echo e($createRoute); ?>" style="display:flex;align-items:center;gap:8px;padding:11px 20px;background:#ff8a3d;color:white;border-radius:10px;font-size:13.5px;font-weight:600;text-decoration:none;box-shadow:0 2px 6px rgba(255,138,61,0.3);"><?php echo e($createLabel); ?></a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($showSearch || count($filters) > 0): ?>
        <div style="background:white;border-radius:14px;padding:16px 20px;box-shadow:0 1px 3px rgba(0,0,0,0.04);flex-shrink:0;">
            <form method="GET" class="dt-filter-form" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                <input type="hidden" name="per_page" value="<?php echo e(request('per_page', $defaultPerPage)); ?>">

                <?php if($showSearch): ?>
                    <div style="flex:1;min-width:180px;max-width:320px;position:relative;">
                        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#9ca3af;">🔍</span>
                        <input name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e($searchPlaceholder); ?>"
                               class="dt-search-input"
                               style="width:100%;padding:11px 14px 11px 40px;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;background:#fafafa;outline:none;font-family:inherit;">
                    </div>
                <?php endif; ?>

                <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(($filter['type'] ?? 'select') === 'select'): ?>
                        <select name="<?php echo e($filter['name']); ?>" style="padding:11px 14px;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;background:#fafafa;font-family:inherit;color:#374151;">
                            <option value=""><?php echo e($filter['placeholder'] ?? 'All'); ?></option>
                            <?php $__currentLoopData = $filter['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php echo e(request($filter['name']) == $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    <?php elseif(($filter['type'] ?? '') === 'date'): ?>
                        <input name="<?php echo e($filter['name']); ?>" type="date" value="<?php echo e(request($filter['name'])); ?>"
                               style="padding:11px 14px;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;background:#fafafa;font-family:inherit;color:#374151;">
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if(request()->hasAny(array_merge(['search'], array_column($filters, 'name')))): ?>
                    <a href="<?php echo e(url()->current()); ?>" style="padding:11px 16px;color:#dc2626;font-size:13px;text-decoration:none;font-weight:600;background:#fef2f2;border-radius:10px;">✕ Clear</a>
                <?php endif; ?>
            </form>
        </div>
    <?php endif; ?>

    
    <div style="background:white;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.04);flex:1 1 auto;min-height:0;display:flex;flex-direction:column;">

        <div style="flex:1 1 auto;min-height:0;overflow-y:auto;overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13.5px;">
                <thead style="position:sticky;top:0;z-index:2;">
                    <tr style="background:#f9fafb;">
                        <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $isString = is_string($col);
                                $label = $isString ? \Illuminate\Support\Str::headline($col) : ($col['label'] ?? '');
                                $align = $isString ? 'left' : ($col['align'] ?? 'left');
                            ?>
                            <th style="padding:14px 18px;text-align:<?php echo e($align); ?>;font-weight:600;color:#6b7280;font-size:11.5px;text-transform:uppercase;letter-spacing:0.6px;background:#f9fafb;">
                                <?php echo e($label); ?>

                            </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if(count($rowActions) > 0): ?>
                            <th style="padding:14px 18px;text-align:right;background:#f9fafb;"></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr style="border-bottom:1px solid #f3f4f6;">
                            <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $isString = is_string($col);
                                    $field = $isString ? $col : ($col['field'] ?? null);
                                    $type = $isString ? 'text' : ($col['type'] ?? 'text');
                                    $align = $isString ? 'left' : ($col['align'] ?? 'left');
                                    $value = $field ? data_get($item, $field) : null;
                                ?>
                                <td style="padding:16px 18px;text-align:<?php echo e($align); ?>;">
                                    <?php echo \App\Helpers\TableRenderer::render($item, $field, $value, $type, $isString ? [] : $col); ?>

                                </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if(count($rowActions) > 0): ?>
                                <td style="padding:16px 18px;text-align:right;">
                                    <div style="display:flex;justify-content:flex-end;gap:6px;">
                                        <?php $__currentLoopData = $rowActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(isset($action['render'])): ?>
                                                <?php echo $action['render']($item); ?>

                                            <?php elseif(isset($action['route'])): ?>
                                                <?php
                                                    $url = is_callable($action['route']) ? $action['route']($item) : route($action['route'], $item);
                                                    $icon = $action['icon'] ?? '👁️';
                                                ?>
                                                <a href="<?php echo e($url); ?>" title="<?php echo e($action['label'] ?? 'View'); ?>"
                                                   style="display:inline-flex;width:34px;height:34px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;align-items:center;justify-content:center;text-decoration:none;font-size:14px;">
                                                    <?php echo e($icon); ?>

                                                </a>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="<?php echo e(count($columns) + (count($rowActions) > 0 ? 1 : 0)); ?>" style="padding:60px 20px;text-align:center;color:#9ca3af;">
                                <div style="font-size:52px;margin-bottom:12px;"><?php echo e($emptyIcon); ?></div>
                                <div style="font-size:16px;font-weight:600;color:#6b7280;margin-bottom:6px;"><?php echo e($emptyMessage); ?></div>
                                <?php if($emptyActionUrl): ?>
                                    <a href="<?php echo e($emptyActionUrl); ?>" style="display:inline-block;margin-top:12px;padding:10px 20px;background:#ff8a3d;color:white;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;"><?php echo e($emptyActionLabel ?? 'Add New'); ?></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if(method_exists($items, 'total') && $items->total() > 0): ?>
            <div style="padding:16px 20px;border-top:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;background:#fafafa;flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <label style="font-size:13px;color:#6b7280;">Rows per page:</label>
                        <select class="dt-per-page" style="padding:7px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;background:white;font-family:inherit;color:#374151;cursor:pointer;font-weight:600;">
                            <?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($opt); ?>" <?php echo e(request('per_page', $defaultPerPage) == $opt ? 'selected' : ''); ?>><?php echo e($opt); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div style="font-size:13px;color:#6b7280;">
                        Showing <strong style="color:#1a1a1a;"><?php echo e($items->firstItem()); ?></strong>–<strong style="color:#1a1a1a;"><?php echo e($items->lastItem()); ?></strong> of <strong style="color:#1a1a1a;"><?php echo e($items->total()); ?></strong>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:6px;">
                    <?php if($items->onFirstPage()): ?>
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#f3f4f6;color:#d1d5db;border-radius:8px;font-size:14px;cursor:not-allowed;">‹</span>
                    <?php else: ?>
                        <a href="<?php echo e($items->previousPageUrl()); ?>" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:white;color:#6b7280;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;text-decoration:none;">‹</a>
                    <?php endif; ?>

                    <?php
                        $cur = $items->currentPage();
                        $last = $items->lastPage();
                        $s = max(1, $cur - 2);
                        $e = min($last, $cur + 2);
                    ?>

                    <?php if($s > 1): ?>
                        <a href="<?php echo e($items->url(1)); ?>" style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 10px;background:white;color:#6b7280;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">1</a>
                        <?php if($s > 2): ?><span style="color:#9ca3af;">…</span><?php endif; ?>
                    <?php endif; ?>

                    <?php for($i = $s; $i <= $e; $i++): ?>
                        <?php if($i == $cur): ?>
                            <span style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 10px;background:#ff8a3d;color:white;border-radius:8px;font-size:13px;font-weight:700;"><?php echo e($i); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($items->url($i)); ?>" style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 10px;background:white;color:#6b7280;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;"><?php echo e($i); ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if($e < $last): ?>
                        <?php if($e < $last - 1): ?><span style="color:#9ca3af;">…</span><?php endif; ?>
                        <a href="<?php echo e($items->url($last)); ?>" style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 10px;background:white;color:#6b7280;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;"><?php echo e($last); ?></a>
                    <?php endif; ?>

                    <?php if($items->hasMorePages()): ?>
                        <a href="<?php echo e($items->nextPageUrl()); ?>" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:white;color:#6b7280;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;text-decoration:none;">›</a>
                    <?php else: ?>
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#f3f4f6;color:#d1d5db;border-radius:8px;font-size:14px;cursor:not-allowed;">›</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (! $__env->hasRenderedOnce('f52da618-2669-4024-a4d6-11847b3d0a3b')): $__env->markAsRenderedOnce('f52da618-2669-4024-a4d6-11847b3d0a3b'); ?>
<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/components/data-table.blade.php ENDPATH**/ ?>