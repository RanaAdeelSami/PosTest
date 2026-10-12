<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['title' => 'Products','subtitle' => 'Manage your products','searchPlaceholder' => 'Search name or SKU...','exportPdfRoute' => auth()->user()->can('export.inventory') ? route('admin.export.inventory', ['format' => 'pdf'] + request()->query()) : null,'exportExcelRoute' => auth()->user()->can('export.inventory') ? route('admin.export.inventory', ['format' => 'xlsx'] + request()->query()) : null,'refreshRoute' => route('admin.products.index'),'createRoute' => route('admin.products.create'),'createLabel' => '➕ Add Product','filters' => [
        ['name' => 'category_id', 'placeholder' => 'All Categories', 'options' => $categories],
        ['name' => 'stock_status', 'placeholder' => 'All Stock', 'options' => ['out' => 'Out of Stock', 'low' => 'Low (1-10)', 'ok' => 'OK (>10)']],
    ],'items' => $products,'columns' => [
        ['field' => 'name', 'label' => 'Product', 'type' => 'bold'],
        ['field' => 'sku', 'label' => 'SKU', 'type' => 'monospace'],
        ['field' => 'category.name', 'label' => 'Category'],
        ['field' => 'cost_price', 'label' => 'Cost', 'type' => 'money', 'align' => 'right'],
        ['field' => 'price', 'label' => 'Price', 'type' => 'money', 'align' => 'right'],
        ['field' => 'stock', 'label' => 'Stock', 'type' => 'stock', 'align' => 'right'],
    ],'rowActions' => [
        ['route' => 'admin.products.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($p) {
            $url = route('admin.products.destroy', $p);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this product?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ],'emptyIcon' => '📦','emptyMessage' => 'No products found','emptyActionUrl' => route('admin.products.create'),'emptyActionLabel' => '+ Add Product']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Products','subtitle' => 'Manage your products','search-placeholder' => 'Search name or SKU...','export-pdf-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->can('export.inventory') ? route('admin.export.inventory', ['format' => 'pdf'] + request()->query()) : null),'export-excel-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->can('export.inventory') ? route('admin.export.inventory', ['format' => 'xlsx'] + request()->query()) : null),'refresh-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.products.index')),'create-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.products.create')),'create-label' => '➕ Add Product','filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'category_id', 'placeholder' => 'All Categories', 'options' => $categories],
        ['name' => 'stock_status', 'placeholder' => 'All Stock', 'options' => ['out' => 'Out of Stock', 'low' => 'Low (1-10)', 'ok' => 'OK (>10)']],
    ]),'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($products),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['field' => 'name', 'label' => 'Product', 'type' => 'bold'],
        ['field' => 'sku', 'label' => 'SKU', 'type' => 'monospace'],
        ['field' => 'category.name', 'label' => 'Category'],
        ['field' => 'cost_price', 'label' => 'Cost', 'type' => 'money', 'align' => 'right'],
        ['field' => 'price', 'label' => 'Price', 'type' => 'money', 'align' => 'right'],
        ['field' => 'stock', 'label' => 'Stock', 'type' => 'stock', 'align' => 'right'],
    ]),'row-actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['route' => 'admin.products.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($p) {
            $url = route('admin.products.destroy', $p);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this product?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ]),'empty-icon' => '📦','empty-message' => 'No products found','empty-action-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.products.create')),'empty-action-label' => '+ Add Product']); ?>
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/products/index.blade.php ENDPATH**/ ?>