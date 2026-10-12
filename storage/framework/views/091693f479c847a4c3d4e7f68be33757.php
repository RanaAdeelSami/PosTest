<?php $__env->startSection('content'); ?>
<div style="display:flex;flex-direction:column;height:100%;gap:16px;">

    
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;flex-shrink:0;">
        <div>
            <h1 style="font-size:24px;font-weight:700;color:#1a1a1a;margin-bottom:4px;">Supplier Returns</h1>
            <p style="color:#6b7280;font-size:14px;">Returns to suppliers</p>
        </div>

        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="<?php echo e(route('admin.supplier-returns.index')); ?>"
               title="Refresh"
               style="display:flex;align-items:center;justify-content:center;width:42px;height:42px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;border-radius:10px;text-decoration:none;font-size:16px;">
                🔄
            </a>

            <a href="<?php echo e(route('admin.supplier-returns.create')); ?>"
               style="display:flex;align-items:center;gap:8px;padding:11px 20px;background:#dc2626;color:white;border-radius:10px;font-size:13.5px;font-weight:600;text-decoration:none;box-shadow:0 2px 6px rgba(220,38,38,0.3);">
                ➕ New Return
            </a>
        </div>
    </div>

    
    <?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['items' => $returns,'columns' => [
            ['label' => 'Return #', 'render' => fn($r) => '<span style=\'font-family:monospace;font-size:12.5px;color:#1e40af;font-weight:600;\'>'.e($r->return_number).'</span>'],
            ['label' => 'Date', 'render' => fn($r) => '<span style=\'color:#374151;font-weight:500;\'>'.e($r->return_date->format('d M Y')).'</span>'],
            ['label' => 'Supplier', 'render' => fn($r) => '<span style=\'font-weight:600;color:#1a1a1a;\'>'.e($r->supplier->name ?? '—').'</span>'],
            ['label' => 'Branch', 'render' => fn($r) => '<span style=\'color:#6b7280;\'>'.e($r->branch->name ?? '—').'</span>'],
            ['label' => 'Amount', 'align' => 'right', 'render' => fn($r) => '<span style=\'font-weight:700;color:#dc2626;font-size:14px;\'>'.number_format($r->total, 2).'</span>'],
            ['label' => 'Settlement', 'render' => fn($r) => '<span style=\'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>'.e(ucfirst(str_replace('_',' ',$r->settlement))).'</span>'],
            ['label' => '', 'align' => 'right', 'render' => function($r) {
                $viewUrl = route('admin.supplier-returns.show', $r);
                $deleteUrl = route('admin.supplier-returns.destroy', $r);
                $csrf = csrf_field();
                $method = method_field('DELETE');
                return '<div style=\'display:flex;justify-content:flex-end;gap:6px;\'>'
                    .'<a href=\''.$viewUrl.'\' title=\'View\' style=\'display:inline-flex;width:34px;height:34px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;align-items:center;justify-content:center;text-decoration:none;font-size:14px;\'>👁️</a>'
                    .'<form method=\'POST\' action=\''.$deleteUrl.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Reverse this return?&quot;)\'>'.$csrf.$method.'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>'
                    .'</div>';
            }],
        ],'emptyIcon' => '↩️','emptyMessage' => 'No supplier returns found','emptyActionUrl' => route('admin.supplier-returns.create'),'emptyActionLabel' => '+ New Return']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($returns),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Return #', 'render' => fn($r) => '<span style=\'font-family:monospace;font-size:12.5px;color:#1e40af;font-weight:600;\'>'.e($r->return_number).'</span>'],
            ['label' => 'Date', 'render' => fn($r) => '<span style=\'color:#374151;font-weight:500;\'>'.e($r->return_date->format('d M Y')).'</span>'],
            ['label' => 'Supplier', 'render' => fn($r) => '<span style=\'font-weight:600;color:#1a1a1a;\'>'.e($r->supplier->name ?? '—').'</span>'],
            ['label' => 'Branch', 'render' => fn($r) => '<span style=\'color:#6b7280;\'>'.e($r->branch->name ?? '—').'</span>'],
            ['label' => 'Amount', 'align' => 'right', 'render' => fn($r) => '<span style=\'font-weight:700;color:#dc2626;font-size:14px;\'>'.number_format($r->total, 2).'</span>'],
            ['label' => 'Settlement', 'render' => fn($r) => '<span style=\'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>'.e(ucfirst(str_replace('_',' ',$r->settlement))).'</span>'],
            ['label' => '', 'align' => 'right', 'render' => function($r) {
                $viewUrl = route('admin.supplier-returns.show', $r);
                $deleteUrl = route('admin.supplier-returns.destroy', $r);
                $csrf = csrf_field();
                $method = method_field('DELETE');
                return '<div style=\'display:flex;justify-content:flex-end;gap:6px;\'>'
                    .'<a href=\''.$viewUrl.'\' title=\'View\' style=\'display:inline-flex;width:34px;height:34px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;align-items:center;justify-content:center;text-decoration:none;font-size:14px;\'>👁️</a>'
                    .'<form method=\'POST\' action=\''.$deleteUrl.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Reverse this return?&quot;)\'>'.$csrf.$method.'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>'
                    .'</div>';
            }],
        ]),'empty-icon' => '↩️','empty-message' => 'No supplier returns found','empty-action-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.supplier-returns.create')),'empty-action-label' => '+ New Return']); ?>
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
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/supplier_returns/index.blade.php ENDPATH**/ ?>