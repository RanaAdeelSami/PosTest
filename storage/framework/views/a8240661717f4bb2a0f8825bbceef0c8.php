

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['title' => 'Suppliers','subtitle' => 'Manage your suppliers','searchPlaceholder' => 'Search name, company, phone, email...','refreshRoute' => route('admin.suppliers.index'),'createRoute' => route('admin.suppliers.create'),'createLabel' => '➕ New Supplier','items' => $suppliers,'columns' => [
        ['field' => 'name', 'label' => 'Name', 'type' => 'bold'],
        ['field' => 'company_name', 'label' => 'Company', 'type' => 'muted'],
        ['field' => 'phone', 'label' => 'Phone', 'type' => 'muted'],
        ['field' => 'email', 'label' => 'Email', 'type' => 'muted'],
        ['field' => 'opening_balance', 'label' => 'Opening', 'type' => 'money', 'align' => 'right'],
        ['field' => 'total_due', 'label' => 'Total Due', 'type' => 'money-red', 'align' => 'right'],
    ],'rowActions' => [
        ['route' => 'admin.suppliers.show', 'icon' => '👁️', 'label' => 'View'],
        ['route' => 'admin.suppliers.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($s) {
            $url = route('admin.suppliers.destroy', $s);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this supplier?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ],'emptyIcon' => '🚚','emptyMessage' => 'No suppliers found','emptyActionUrl' => route('admin.suppliers.create'),'emptyActionLabel' => '+ New Supplier']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Suppliers','subtitle' => 'Manage your suppliers','search-placeholder' => 'Search name, company, phone, email...','refresh-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.suppliers.index')),'create-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.suppliers.create')),'create-label' => '➕ New Supplier','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($suppliers),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['field' => 'name', 'label' => 'Name', 'type' => 'bold'],
        ['field' => 'company_name', 'label' => 'Company', 'type' => 'muted'],
        ['field' => 'phone', 'label' => 'Phone', 'type' => 'muted'],
        ['field' => 'email', 'label' => 'Email', 'type' => 'muted'],
        ['field' => 'opening_balance', 'label' => 'Opening', 'type' => 'money', 'align' => 'right'],
        ['field' => 'total_due', 'label' => 'Total Due', 'type' => 'money-red', 'align' => 'right'],
    ]),'row-actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['route' => 'admin.suppliers.show', 'icon' => '👁️', 'label' => 'View'],
        ['route' => 'admin.suppliers.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($s) {
            $url = route('admin.suppliers.destroy', $s);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this supplier?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ]),'empty-icon' => '🚚','empty-message' => 'No suppliers found','empty-action-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.suppliers.create')),'empty-action-label' => '+ New Supplier']); ?>
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/suppliers/index.blade.php ENDPATH**/ ?>