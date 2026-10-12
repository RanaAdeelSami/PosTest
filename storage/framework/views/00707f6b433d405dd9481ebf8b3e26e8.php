<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['title' => 'Branches','subtitle' => 'Manage your branches','searchPlaceholder' => 'Search branch name or code...','refreshRoute' => route('admin.branches.index'),'createRoute' => route('admin.branches.create'),'createLabel' => '➕ New Branch','filters' => [
        ['name' => 'company_id', 'placeholder' => 'All Companies', 'options' => $companies->toArray()],
    ],'items' => $branches,'columns' => [
        ['field' => 'name', 'label' => 'Branch Name', 'type' => 'bold'],
        ['field' => 'code', 'label' => 'Code', 'type' => 'monospace'],
        ['field' => 'company.name', 'label' => 'Company'],
        ['field' => 'phone', 'label' => 'Phone', 'type' => 'muted'],
        ['field' => 'address', 'label' => 'Address', 'type' => 'muted'],
        ['label' => 'Status', 'align' => 'center', 'render' => fn($b) => $b->is_active
            ? '<span style=\'background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Active</span>'
            : '<span style=\'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Inactive</span>'
        ],
    ],'rowActions' => [
        ['route' => 'admin.branches.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($b) {
            $url = route('admin.branches.destroy', $b);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this branch?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ],'emptyIcon' => '🏢','emptyMessage' => 'No branches found','emptyActionUrl' => route('admin.branches.create'),'emptyActionLabel' => '+ New Branch']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Branches','subtitle' => 'Manage your branches','search-placeholder' => 'Search branch name or code...','refresh-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.branches.index')),'create-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.branches.create')),'create-label' => '➕ New Branch','filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'company_id', 'placeholder' => 'All Companies', 'options' => $companies->toArray()],
    ]),'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($branches),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['field' => 'name', 'label' => 'Branch Name', 'type' => 'bold'],
        ['field' => 'code', 'label' => 'Code', 'type' => 'monospace'],
        ['field' => 'company.name', 'label' => 'Company'],
        ['field' => 'phone', 'label' => 'Phone', 'type' => 'muted'],
        ['field' => 'address', 'label' => 'Address', 'type' => 'muted'],
        ['label' => 'Status', 'align' => 'center', 'render' => fn($b) => $b->is_active
            ? '<span style=\'background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Active</span>'
            : '<span style=\'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Inactive</span>'
        ],
    ]),'row-actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['route' => 'admin.branches.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($b) {
            $url = route('admin.branches.destroy', $b);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this branch?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ]),'empty-icon' => '🏢','empty-message' => 'No branches found','empty-action-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.branches.create')),'empty-action-label' => '+ New Branch']); ?>
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/branches/index.blade.php ENDPATH**/ ?>