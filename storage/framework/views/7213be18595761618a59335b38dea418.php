<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['title' => 'Low Stock Alerts','subtitle' => 'Products with stock ≤ ' . $threshold,'searchPlaceholder' => 'Search product...','refreshRoute' => route('admin.inventory.low-stock'),'backRoute' => route('admin.inventory.report'),'backLabel' => '📊 Full Report','items' => $products,'columns' => [
        ['field' => 'name', 'label' => 'Product', 'type' => 'bold'],
        ['field' => 'sku', 'label' => 'SKU', 'type' => 'monospace'],
        ['field' => 'category.name', 'label' => 'Category'],
        ['field' => 'stock', 'label' => 'Stock', 'type' => 'stock', 'align' => 'right'],
        ['field' => 'price', 'label' => 'Price', 'type' => 'money', 'align' => 'right'],
    ],'rowActions' => [
        ['route' => 'admin.products.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($p) {
            $url = route('admin.products.destroy', $p);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this product?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ],'emptyIcon' => '✅','emptyMessage' => 'All products are well-stocked']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Low Stock Alerts','subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Products with stock ≤ ' . $threshold),'search-placeholder' => 'Search product...','refresh-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.inventory.low-stock')),'back-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.inventory.report')),'back-label' => '📊 Full Report','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($products),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['field' => 'name', 'label' => 'Product', 'type' => 'bold'],
        ['field' => 'sku', 'label' => 'SKU', 'type' => 'monospace'],
        ['field' => 'category.name', 'label' => 'Category'],
        ['field' => 'stock', 'label' => 'Stock', 'type' => 'stock', 'align' => 'right'],
        ['field' => 'price', 'label' => 'Price', 'type' => 'money', 'align' => 'right'],
    ]),'row-actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['route' => 'admin.products.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($p) {
            $url = route('admin.products.destroy', $p);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this product?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ]),'empty-icon' => '✅','empty-message' => 'All products are well-stocked']); ?>
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/inventory/low_stock.blade.php ENDPATH**/ ?>