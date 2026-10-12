<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['title' => 'Categories','subtitle' => 'Manage your categories','searchPlaceholder' => 'Search category name...','refreshRoute' => route('admin.categories.index'),'createRoute' => route('admin.categories.create'),'createLabel' => '➕ New Category','filters' => [
        ['name' => 'company_id', 'placeholder' => 'All Companies', 'options' => $companies->toArray()],
    ],'items' => $categories,'columns' => [
        ['field' => 'name', 'label' => 'Category', 'type' => 'bold'],
        ['field' => 'description', 'label' => 'Description', 'type' => 'muted'],
        ['field' => 'created_at', 'label' => 'Created', 'type' => 'date'],
        ['label' => 'Status', 'align' => 'center', 'render' => fn($c) => $c->is_active
            ? '<span style=\'background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Active</span>'
            : '<span style=\'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Inactive</span>'
        ],
    ],'rowActions' => [
        ['render' => function($c) {
            $json = htmlspecialchars(json_encode(['id' => $c->id, 'name' => $c->name, 'description' => $c->description, 'company_id' => $c->company_id, 'is_active' => $c->is_active]), ENT_QUOTES, 'UTF-8');
            return '<button type=\'button\' onclick=\'openCategoryEdit('.$json.')\' title=\'Edit\' style=\'display:inline-flex;width:34px;height:34px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;align-items:center;justify-content:center;cursor:pointer;font-size:14px;\'>✏️</button>';
        }],
        ['render' => function($c) {
            $url = route('admin.categories.destroy', $c);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this category?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ],'emptyIcon' => '📁','emptyMessage' => 'No categories found']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Categories','subtitle' => 'Manage your categories','search-placeholder' => 'Search category name...','refresh-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.categories.index')),'create-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.categories.create')),'create-label' => '➕ New Category','filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'company_id', 'placeholder' => 'All Companies', 'options' => $companies->toArray()],
    ]),'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['field' => 'name', 'label' => 'Category', 'type' => 'bold'],
        ['field' => 'description', 'label' => 'Description', 'type' => 'muted'],
        ['field' => 'created_at', 'label' => 'Created', 'type' => 'date'],
        ['label' => 'Status', 'align' => 'center', 'render' => fn($c) => $c->is_active
            ? '<span style=\'background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Active</span>'
            : '<span style=\'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Inactive</span>'
        ],
    ]),'row-actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['render' => function($c) {
            $json = htmlspecialchars(json_encode(['id' => $c->id, 'name' => $c->name, 'description' => $c->description, 'company_id' => $c->company_id, 'is_active' => $c->is_active]), ENT_QUOTES, 'UTF-8');
            return '<button type=\'button\' onclick=\'openCategoryEdit('.$json.')\' title=\'Edit\' style=\'display:inline-flex;width:34px;height:34px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;align-items:center;justify-content:center;cursor:pointer;font-size:14px;\'>✏️</button>';
        }],
        ['render' => function($c) {
            $url = route('admin.categories.destroy', $c);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this category?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ]),'empty-icon' => '📁','empty-message' => 'No categories found']); ?>
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


<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'categoryModal','title' => 'Add Category','maxWidth' => '520px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'categoryModal','title' => 'Add Category','max-width' => '520px']); ?>
    <form id="categoryForm" method="POST" action="">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="_method" id="formMethod" value="POST">

        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">
                Category Name <span style="color:#dc2626;">*</span>
            </label>
            <input type="text" name="name" id="catName" required
                   style="width:100%;padding:11px 14px;border:1px solid #e5e7eb;border-radius:10px;font-size:13.5px;outline:none;font-family:inherit;">
        </div>

        <?php if(auth()->user()->hasRole('super_admin') && $companies->count() > 0): ?>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Company</label>
                <select name="company_id" id="catCompany"
                        style="width:100%;padding:11px 14px;border:1px solid #e5e7eb;border-radius:10px;font-size:13.5px;outline:none;font-family:inherit;background:white;">
                    <option value="">-- Select --</option>
                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        <?php endif; ?>

        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Description</label>
            <textarea name="description" id="catDescription" rows="3"
                      style="width:100%;padding:11px 14px;border:1px solid #e5e7eb;border-radius:10px;font-size:13.5px;outline:none;font-family:inherit;resize:vertical;"></textarea>
        </div>

        <div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;">
            <label style="font-size:13px;font-weight:600;color:#374151;">
                Status <span style="color:#dc2626;">*</span>
            </label>
            <div style="display:flex;align-items:center;gap:10px;">
                <label style="position:relative;display:inline-block;width:46px;height:24px;">
                    <input type="checkbox" name="is_active" id="catActive" value="1" checked style="opacity:0;width:0;height:0;">
                    <span id="toggleTrack" style="position:absolute;cursor:pointer;inset:0;background:#16a34a;border-radius:24px;transition:.3s;" onclick="toggleSwitch()">
                        <span id="toggleThumb" style="position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:white;border-radius:50%;transition:.3s;transform:translateX(22px);"></span>
                    </span>
                </label>
                <span id="toggleLabel" style="font-size:12px;color:#16a34a;font-weight:600;">Active</span>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:16px;border-top:1px solid #f0f0f0;">
            <button type="button" onclick="closeModal('categoryModal')"
                    style="padding:10px 22px;background:#1e3a8a;color:white;border:none;border-radius:8px;font-size:13.5px;font-weight:600;cursor:pointer;font-family:inherit;">
                Cancel
            </button>
            <button type="submit" id="submitBtn"
                    style="padding:10px 22px;background:#ff8a3d;color:white;border:none;border-radius:8px;font-size:13.5px;font-weight:600;cursor:pointer;font-family:inherit;">
                Add Category
            </button>
        </div>
    </form>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
    document.body.style.overflow = '';
}
function toggleSwitch() {
    var cb = document.getElementById('catActive');
    var track = document.getElementById('toggleTrack');
    var thumb = document.getElementById('toggleThumb');
    var label = document.getElementById('toggleLabel');
    cb.checked = !cb.checked;
    if (cb.checked) {
        track.style.background = '#16a34a';
        thumb.style.transform = 'translateX(22px)';
        label.textContent = 'Active';
        label.style.color = '#16a34a';
    } else {
        track.style.background = '#d1d5db';
        thumb.style.transform = 'translateX(0)';
        label.textContent = 'Inactive';
        label.style.color = '#6b7280';
    }
}
function openCategoryAdd() {
    document.getElementById('categoryForm').action = "<?php echo e(route('admin.categories.store')); ?>";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('catName').value = '';
    document.getElementById('catDescription').value = '';
    document.getElementById('catActive').checked = true;
    document.getElementById('toggleTrack').style.background = '#16a34a';
    document.getElementById('toggleThumb').style.transform = 'translateX(22px)';
    document.getElementById('toggleLabel').textContent = 'Active';
    document.getElementById('toggleLabel').style.color = '#16a34a';
    var comp = document.getElementById('catCompany');
    if (comp) comp.value = '';
    document.querySelector('#categoryModal h2').textContent = 'Add Category';
    document.getElementById('submitBtn').textContent = 'Add Category';
    openModal('categoryModal');
}
function openCategoryEdit(cat) {
    document.getElementById('categoryForm').action = "/admin/categories/" + cat.id;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('catName').value = cat.name || '';
    document.getElementById('catDescription').value = cat.description || '';
    document.getElementById('catActive').checked = !!cat.is_active;
    var track = document.getElementById('toggleTrack');
    var thumb = document.getElementById('toggleThumb');
    var label = document.getElementById('toggleLabel');
    if (cat.is_active) {
        track.style.background = '#16a34a';
        thumb.style.transform = 'translateX(22px)';
        label.textContent = 'Active';
        label.style.color = '#16a34a';
    } else {
        track.style.background = '#d1d5db';
        thumb.style.transform = 'translateX(0)';
        label.textContent = 'Inactive';
        label.style.color = '#6b7280';
    }
    var comp = document.getElementById('catCompany');
    if (comp) comp.value = cat.company_id || '';
    document.querySelector('#categoryModal h2').textContent = 'Edit Category';
    document.getElementById('submitBtn').textContent = 'Update Category';
    openModal('categoryModal');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal('categoryModal');
});

// ⚠️ Intercept "New Category" button — open modal instead of navigating
document.addEventListener('DOMContentLoaded', function() {
    var createUrl = "<?php echo e(route('admin.categories.create')); ?>";
    document.querySelectorAll('a').forEach(function(link) {
        if (link.getAttribute('href') === createUrl) {
            link.removeAttribute('href');
            link.style.cursor = 'pointer';
            link.addEventListener('click', function(e) {
                e.preventDefault();
                openCategoryAdd();
            });
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>