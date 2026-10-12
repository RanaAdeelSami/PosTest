

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h1 class="text-2xl font-bold">Profit Report</h1>
</div>
<div style="display:flex;flex-direction:column;height:100%;gap:16px;">

    
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;flex-shrink:0;">
        <div style="background:white;border-radius:12px;padding:16px;box-shadow:0 1px 3px rgba(0,0,0,0.04);border-left:4px solid #2563eb;">
            <div style="color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Total Sales</div>
            <div style="font-size:22px;font-weight:800;color:#2563eb;margin-top:4px;"><?php echo e(number_format($summary['total_sales'] ?? 0, 2)); ?></div>
        </div>
        <div style="background:white;border-radius:12px;padding:16px;box-shadow:0 1px 3px rgba(0,0,0,0.04);border-left:4px solid #6b7280;">
            <div style="color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Total Cost</div>
            <div style="font-size:22px;font-weight:800;color:#374151;margin-top:4px;"><?php echo e(number_format($summary['total_cost'] ?? 0, 2)); ?></div>
        </div>
        <div style="background:white;border-radius:12px;padding:16px;box-shadow:0 1px 3px rgba(0,0,0,0.04);border-left:4px solid #16a34a;">
            <div style="color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Total Profit</div>
            <div style="font-size:22px;font-weight:800;color:<?php echo e(($summary['total_profit'] ?? 0) >= 0 ? '#16a34a' : '#dc2626'); ?>;margin-top:4px;">
                <?php echo e(number_format($summary['total_profit'] ?? 0, 2)); ?>

            </div>
        </div>
        <div style="background:white;border-radius:12px;padding:16px;box-shadow:0 1px 3px rgba(0,0,0,0.04);border-left:4px solid #d97706;">
            <div style="color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Margin</div>
            <div style="font-size:22px;font-weight:800;color:<?php echo e(($summary['margin_percent'] ?? 0) >= 0 ? '#16a34a' : '#dc2626'); ?>;margin-top:4px;">
                <?php echo e(number_format($summary['margin_percent'] ?? 0, 1)); ?>%
            </div>
        </div>
    </div>

    
    <?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['searchPlaceholder' => 'Search invoice or customer...','exportPdfRoute' => auth()->user()->can('export.profit') ? route('admin.export.profit', ['format' => 'pdf'] + request()->query()) : null,'exportExcelRoute' => auth()->user()->can('export.profit') ? route('admin.export.profit', ['format' => 'xlsx'] + request()->query()) : null,'refreshRoute' => route('admin.sales.profit-report'),'backRoute' => route('admin.sales.index'),'backLabel' => '← All Sales','filters' => [
            ['name' => 'from_date', 'type' => 'date'],
            ['name' => 'to_date', 'type' => 'date'],
            ['name' => 'branch_id', 'placeholder' => 'All Branches', 'options' => $branches],
        ],'items' => $sales,'columns' => [
            ['field' => 'invoice_number', 'label' => 'Invoice', 'type' => 'monospace'],
            ['field' => 'created_at', 'label' => 'Date', 'type' => 'datetime'],
            ['field' => 'customer_name', 'label' => 'Customer', 'type' => 'bold'],
            ['field' => 'branch.name', 'label' => 'Branch', 'type' => 'muted'],
            ['field' => 'total', 'label' => 'Sale', 'type' => 'money', 'align' => 'right'],
            ['label' => 'Cost', 'align' => 'right', 'render' => fn($s) => '<span style=\'color:#6b7280;font-weight:600;\'>'.number_format($s->total_cost ?? 0, 2).'</span>'],
            ['label' => 'Profit', 'align' => 'right', 'render' => function($s) {
                $profit = $s->total_profit ?? 0;
                $color = $profit >= 0 ? '#16a34a' : '#dc2626';
                return '<span style=\'font-weight:700;color:'.$color.';font-size:14px;\'>'.number_format($profit, 2).'</span>';
            }],
            ['label' => 'Margin', 'align' => 'right', 'render' => function($s) {
                $margin = $s->margin_percent ?? 0;
                $color = $margin >= 0 ? '#16a34a' : '#dc2626';
                return '<span style=\'font-weight:600;color:'.$color.';\'>'.number_format($margin, 1).'%</span>';
            }],
        ],'rowActions' => [],'emptyIcon' => '📈','emptyMessage' => 'No sales found']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['search-placeholder' => 'Search invoice or customer...','export-pdf-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->can('export.profit') ? route('admin.export.profit', ['format' => 'pdf'] + request()->query()) : null),'export-excel-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->can('export.profit') ? route('admin.export.profit', ['format' => 'xlsx'] + request()->query()) : null),'refresh-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.sales.profit-report')),'back-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.sales.index')),'back-label' => '← All Sales','filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['name' => 'from_date', 'type' => 'date'],
            ['name' => 'to_date', 'type' => 'date'],
            ['name' => 'branch_id', 'placeholder' => 'All Branches', 'options' => $branches],
        ]),'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sales),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['field' => 'invoice_number', 'label' => 'Invoice', 'type' => 'monospace'],
            ['field' => 'created_at', 'label' => 'Date', 'type' => 'datetime'],
            ['field' => 'customer_name', 'label' => 'Customer', 'type' => 'bold'],
            ['field' => 'branch.name', 'label' => 'Branch', 'type' => 'muted'],
            ['field' => 'total', 'label' => 'Sale', 'type' => 'money', 'align' => 'right'],
            ['label' => 'Cost', 'align' => 'right', 'render' => fn($s) => '<span style=\'color:#6b7280;font-weight:600;\'>'.number_format($s->total_cost ?? 0, 2).'</span>'],
            ['label' => 'Profit', 'align' => 'right', 'render' => function($s) {
                $profit = $s->total_profit ?? 0;
                $color = $profit >= 0 ? '#16a34a' : '#dc2626';
                return '<span style=\'font-weight:700;color:'.$color.';font-size:14px;\'>'.number_format($profit, 2).'</span>';
            }],
            ['label' => 'Margin', 'align' => 'right', 'render' => function($s) {
                $margin = $s->margin_percent ?? 0;
                $color = $margin >= 0 ? '#16a34a' : '#dc2626';
                return '<span style=\'font-weight:600;color:'.$color.';\'>'.number_format($margin, 1).'%</span>';
            }],
        ]),'row-actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([]),'empty-icon' => '📈','empty-message' => 'No sales found']); ?>
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/sales/profit_report.blade.php ENDPATH**/ ?>