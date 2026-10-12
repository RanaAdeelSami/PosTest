

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['title' => 'Warehouses','subtitle' => 'Manage your warehouses','searchPlaceholder' => 'Search warehouse name or code...','refreshRoute' => route('admin.warehouses.index'),'createRoute' => route('admin.warehouses.create'),'createLabel' => '➕ New Warehouse','items' => $warehouses,'columns' => [
        ['field' => 'name', 'label' => 'Warehouse', 'type' => 'bold'],
        ['field' => 'code', 'label' => 'Code', 'type' => 'monospace'],
        ['field' => 'branch.name', 'label' => 'Branch', 'type' => 'muted'],
        ['field' => 'stocks_count', 'label' => 'Products', 'align' => 'center'],
        ['field' => 'phone', 'label' => 'Phone', 'type' => 'muted'],
        ['label' => 'Status', 'align' => 'center', 'render' => fn($w) => $w->is_active
            ? '<span style=\'background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Active</span>'
            : '<span style=\'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Inactive</span>'
        ],
    ],'rowActions' => [
        ['route' => 'admin.warehouses.show', 'icon' => '👁️', 'label' => 'View'],
        ['route' => 'admin.warehouses.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($w) {
            $url = route('admin.warehouses.destroy', $w);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this warehouse?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ],'emptyIcon' => '🏭','emptyMessage' => 'No warehouses found','emptyActionUrl' => route('admin.warehouses.create'),'emptyActionLabel' => '+ New Warehouse']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Warehouses','subtitle' => 'Manage your warehouses','search-placeholder' => 'Search warehouse name or code...','refresh-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.warehouses.index')),'create-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.warehouses.create')),'create-label' => '➕ New Warehouse','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($warehouses),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['field' => 'name', 'label' => 'Warehouse', 'type' => 'bold'],
        ['field' => 'code', 'label' => 'Code', 'type' => 'monospace'],
        ['field' => 'branch.name', 'label' => 'Branch', 'type' => 'muted'],
        ['field' => 'stocks_count', 'label' => 'Products', 'align' => 'center'],
        ['field' => 'phone', 'label' => 'Phone', 'type' => 'muted'],
        ['label' => 'Status', 'align' => 'center', 'render' => fn($w) => $w->is_active
            ? '<span style=\'background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Active</span>'
            : '<span style=\'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Inactive</span>'
        ],
    ]),'row-actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['route' => 'admin.warehouses.show', 'icon' => '👁️', 'label' => 'View'],
        ['route' => 'admin.warehouses.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($w) {
            $url = route('admin.warehouses.destroy', $w);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this warehouse?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ]),'empty-icon' => '🏭','empty-message' => 'No warehouses found','empty-action-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.warehouses.create')),'empty-action-label' => '+ New Warehouse']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc8463834ba515134d5c98b88e1a9dc03)): ?>
<?php $attributes = $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03; ?>
<?php unset($__attributesOriginalc8463834ba515134d5c98b88e1a9dc03); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc8463834ba515134d5c98b88e1a9dc03)): ?>
<?php $component = $__componentOriginalc8463834ba515134d5c98b88e1a9dc03; ?>
<?php unset($__componentOriginalc8463834ba515134d5c98b88e1a9dc03); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/warehouses/index.blade.php ENDPATH**/ ?>