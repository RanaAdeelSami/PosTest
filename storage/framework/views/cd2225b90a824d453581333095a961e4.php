<?php $__env->startSection('content'); ?>

<div class="mb-5">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-xl shadow">
            🔐
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Role Permissions</h1>
            <p class="text-xs text-gray-500">Toggle karte hi auto-save ho jata hai</p>
        </div>
        <div class="ml-auto text-xs text-gray-500 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200">
            💡 Super Admin ke paas full access hai
        </div>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">

    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $style = $roleStyle[$role->name] ?? ['color' => 'gray', 'icon' => '👤', 'desc' => ''];
            $c = $colorMap[$style['color']] ?? $colorMap['blue'];
            $rolePerms = $rolePermissions[$role->id] ?? [];
            $totalPerms = \Spatie\Permission\Models\Permission::count();
            $checkedCount = count($rolePerms);
            $percent = $totalPerms > 0 ? round(($checkedCount / $totalPerms) * 100) : 0;
        ?>

        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden hover:shadow-md transition-shadow"
             data-role-card="<?php echo e($role->id); ?>">

            
            <div class="px-4 py-3 <?php echo e($c['bg']); ?> border-b <?php echo e($c['border']); ?>">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-lg"><?php echo e($style['icon']); ?></span>
                        <div>
                            <div class="font-semibold text-sm <?php echo e($c['text']); ?> capitalize">
                                <?php echo e(str_replace('_', ' ', $role->name)); ?>

                            </div>
                            <div class="text-[10px] <?php echo e($c['text3']); ?>"><?php echo e($style['desc']); ?></div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold <?php echo e($c['text']); ?>">
                            <span class="checked-count"><?php echo e($checkedCount); ?></span><span class="text-[10px] font-normal">/<?php echo e($totalPerms); ?></span>
                        </div>
                    </div>
                </div>

                
                <div class="mt-2 h-1 bg-white bg-opacity-70 rounded-full overflow-hidden">
                    <div class="h-full <?php echo e($c['bar']); ?> rounded-full transition-all duration-500 progress-bar"
                         style="width: <?php echo e($percent); ?>%"></div>
                </div>
            </div>

            
            <div class="p-3 space-y-2.5 max-h-[400px] overflow-y-auto">

                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $perms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start gap-2 text-xs">
                        <div class="w-16 shrink-0 font-semibold <?php echo e($c['text3']); ?> capitalize pt-0.5">
                            <?php echo e($group); ?>

                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-1.5 flex-1">
                            <?php $__currentLoopData = $perms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $isChecked = in_array($perm->name, $rolePerms);
                                    $action = explode('.', $perm->name)[1] ?? $perm->name;
                                ?>
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="checkbox"
                                           class="permission-checkbox w-3.5 h-3.5 rounded focus:ring-0 cursor-pointer"
                                           data-role-id="<?php echo e($role->id); ?>"
                                           data-permission="<?php echo e($perm->name); ?>"
                                           <?php echo e($isChecked ? 'checked' : ''); ?>>
                                    <span class="text-[11px] text-gray-700 capitalize"><?php echo e($action); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>


<div id="toast" class="fixed top-6 left-1/2 -translate-x-1/2 bg-green-600 text-white px-5 py-3 rounded-xl shadow-2xl hidden items-center gap-2 z-[100] transition-all duration-300">
    <span class="text-lg">✓</span>
    <span class="text-sm font-medium">Saved successfully</span>
</div>


<div id="savingIndicator" class="fixed top-6 left-1/2 -translate-x-1/2 bg-gray-800 text-white px-5 py-3 rounded-xl shadow-2xl hidden items-center gap-2 z-[100]">
    <div class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></div>
    <span class="text-sm">Saving...</span>
</div>

<script>
var csrfToken = "<?php echo e(csrf_token()); ?>";

document.querySelectorAll('.permission-checkbox').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var roleId = this.dataset.roleId;
        var permission = this.dataset.permission;
        var checked = this.checked;
        var checkbox = this;

        showSaving();
        checkbox.disabled = true;

        fetch("<?php echo e(url('admin/roles')); ?>/" + roleId + "/toggle", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                permission: permission,
                checked: checked
            })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                updateCard(roleId, data.total);
                showToast();
            } else {
                checkbox.checked = !checked;
                showError(data.message || 'Save failed');
            }
        })
        .catch(function(err) {
            checkbox.checked = !checked;
            showError('Network error');
            console.error(err);
        })
        .finally(function() {
            checkbox.disabled = false;
            hideSaving();
        });
    });
});

function updateCard(roleId, total) {
    var card = document.querySelector('[data-role-card="' + roleId + '"]');
    if (!card) return;

    var totalPerms = <?php echo e(\Spatie\Permission\Models\Permission::count()); ?>;
    var percent = totalPerms > 0 ? Math.round((total / totalPerms) * 100) : 0;

    card.querySelector('.checked-count').textContent = total;
    card.querySelector('.progress-bar').style.width = percent + '%';
}

function showToast() {
    var toast = document.getElementById('toast');
    toast.classList.remove('hidden');
    toast.classList.add('flex');
    setTimeout(function() {
        toast.classList.add('hidden');
        toast.classList.remove('flex');
    }, 1500);
}

function showSaving() {
    var el = document.getElementById('savingIndicator');
    el.classList.remove('hidden');
    el.classList.add('flex');
}

function hideSaving() {
    var el = document.getElementById('savingIndicator');
    el.classList.add('hidden');
    el.classList.remove('flex');
}

function showError(msg) {
    var toast = document.getElementById('toast');
    var original = toast.innerHTML;

    toast.innerHTML = '<span class="text-lg">✕</span><span class="text-sm font-medium">' + msg + '</span>';
    toast.classList.remove('bg-green-600');
    toast.classList.add('bg-red-600');

    setTimeout(function() {
        toast.classList.add('hidden');
        toast.classList.remove('flex');
        toast.innerHTML = original;
        toast.classList.remove('bg-red-600');
        toast.classList.add('bg-green-600');
    }, 2000);
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>