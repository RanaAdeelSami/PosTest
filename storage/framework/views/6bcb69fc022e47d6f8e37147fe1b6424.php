

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['title' => 'Sales List','subtitle' => 'Manage your sales','searchPlaceholder' => 'Search invoice, customer, phone...','exportPdfRoute' => auth()->user()->can('export.sales') ? route('admin.export.sales', ['format' => 'pdf'] + request()->query()) : null,'exportExcelRoute' => auth()->user()->can('export.sales') ? route('admin.export.sales', ['format' => 'xlsx'] + request()->query()) : null,'refreshRoute' => route('admin.sales.index'),'createRoute' => route('admin.pos.index'),'createLabel' => '➕ New Sale','filters' => [
        ['name' => 'from_date', 'type' => 'date'],
        ['name' => 'to_date', 'type' => 'date'],
        ['name' => 'branch_id', 'placeholder' => 'All Branches', 'options' => $branches],
        ['name' => 'warehouse_id', 'placeholder' => 'All Warehouses', 'options' => $warehouses],
        ['name' => 'payment_method', 'placeholder' => 'All Payment', 'options' => ['cash' => 'Cash', 'card' => 'Card', 'credit' => 'Credit']],
    ],'items' => $sales,'columns' => [
        ['field' => 'invoice_number', 'label' => 'Invoice', 'type' => 'monospace'],
        ['field' => 'created_at', 'label' => 'Date', 'type' => 'datetime'],
        ['field' => 'customer_name', 'label' => 'Customer', 'type' => 'bold'],
        ['field' => 'branch.name', 'label' => 'Branch', 'type' => 'muted'],
        ['field' => 'warehouse.name', 'label' => 'Warehouse', 'type' => 'warehouse'],
        ['field' => 'user.name', 'label' => 'Cashier', 'type' => 'muted'],
        ['field' => 'total', 'label' => 'Total', 'type' => 'money', 'align' => 'right'],
        ['field' => 'payment_method', 'label' => 'Payment', 'type' => 'payment'],
    ],'rowActions' => [
        ['route' => 'admin.sales.show', 'icon' => '👁️', 'label' => 'View'],
    ],'emptyIcon' => '📭','emptyMessage' => 'No sales found']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Sales List','subtitle' => 'Manage your sales','search-placeholder' => 'Search invoice, customer, phone...','export-pdf-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->can('export.sales') ? route('admin.export.sales', ['format' => 'pdf'] + request()->query()) : null),'export-excel-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->can('export.sales') ? route('admin.export.sales', ['format' => 'xlsx'] + request()->query()) : null),'refresh-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.sales.index')),'create-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.pos.index')),'create-label' => '➕ New Sale','filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'from_date', 'type' => 'date'],
        ['name' => 'to_date', 'type' => 'date'],
        ['name' => 'branch_id', 'placeholder' => 'All Branches', 'options' => $branches],
        ['name' => 'warehouse_id', 'placeholder' => 'All Warehouses', 'options' => $warehouses],
        ['name' => 'payment_method', 'placeholder' => 'All Payment', 'options' => ['cash' => 'Cash', 'card' => 'Card', 'credit' => 'Credit']],
    ]),'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sales),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['field' => 'invoice_number', 'label' => 'Invoice', 'type' => 'monospace'],
        ['field' => 'created_at', 'label' => 'Date', 'type' => 'datetime'],
        ['field' => 'customer_name', 'label' => 'Customer', 'type' => 'bold'],
        ['field' => 'branch.name', 'label' => 'Branch', 'type' => 'muted'],
        ['field' => 'warehouse.name', 'label' => 'Warehouse', 'type' => 'warehouse'],
        ['field' => 'user.name', 'label' => 'Cashier', 'type' => 'muted'],
        ['field' => 'total', 'label' => 'Total', 'type' => 'money', 'align' => 'right'],
        ['field' => 'payment_method', 'label' => 'Payment', 'type' => 'payment'],
    ]),'row-actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['route' => 'admin.sales.show', 'icon' => '👁️', 'label' => 'View'],
    ]),'empty-icon' => '📭','empty-message' => 'No sales found']); ?>
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/sales/index.blade.php ENDPATH**/ ?>