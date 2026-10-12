<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['title' => 'Sales Returns','subtitle' => 'Customer return history','refreshRoute' => route('admin.sales.returns'),'backRoute' => route('admin.sales.index'),'backLabel' => '← All Sales','items' => $returns,'columns' => [
        ['field' => 'return_number', 'label' => 'Return #', 'type' => 'monospace'],
        ['field' => 'created_at', 'label' => 'Date', 'type' => 'datetime'],
        ['field' => 'sale.invoice_number', 'label' => 'Invoice', 'type' => 'monospace'],
        ['field' => 'sale.customer_name', 'label' => 'Customer', 'type' => 'bold'],
        ['field' => 'return_amount', 'label' => 'Amount', 'type' => 'money-red', 'align' => 'right'],
        ['field' => 'refund_method', 'label' => 'Refund', 'type' => 'badge', 'colors' => ['cash' => 'green', 'card' => 'blue', 'credit' => 'yellow']],
    ],'rowActions' => [
        ['route' => 'admin.sales.return.show', 'icon' => '👁️', 'label' => 'View'],
    ],'emptyIcon' => '↩️','emptyMessage' => 'No returns found']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Sales Returns','subtitle' => 'Customer return history','refresh-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.sales.returns')),'back-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.sales.index')),'back-label' => '← All Sales','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($returns),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['field' => 'return_number', 'label' => 'Return #', 'type' => 'monospace'],
        ['field' => 'created_at', 'label' => 'Date', 'type' => 'datetime'],
        ['field' => 'sale.invoice_number', 'label' => 'Invoice', 'type' => 'monospace'],
        ['field' => 'sale.customer_name', 'label' => 'Customer', 'type' => 'bold'],
        ['field' => 'return_amount', 'label' => 'Amount', 'type' => 'money-red', 'align' => 'right'],
        ['field' => 'refund_method', 'label' => 'Refund', 'type' => 'badge', 'colors' => ['cash' => 'green', 'card' => 'blue', 'credit' => 'yellow']],
    ]),'row-actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['route' => 'admin.sales.return.show', 'icon' => '👁️', 'label' => 'View'],
    ]),'empty-icon' => '↩️','empty-message' => 'No returns found']); ?>
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/sales/returns.blade.php ENDPATH**/ ?>