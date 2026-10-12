

<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-bold mb-4">Edit Warehouse</h1>

<form method="POST" action="<?php echo e(route('admin.warehouses.update', $warehouse)); ?>" class="bg-white p-6 rounded-lg shadow max-w-xl">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Name *</label>
        <input name="name" value="<?php echo e(old('name', $warehouse->name)); ?>" class="w-full border rounded p-2" required>
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Code</label>
        <input name="code" value="<?php echo e(old('code', $warehouse->code)); ?>" class="w-full border rounded p-2">
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Branch</label>
        <select name="branch_id" class="w-full border rounded p-2">
            <option value="">-- None --</option>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($id); ?>" <?php echo e(old('branch_id', $warehouse->branch_id) == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Address</label>
        <textarea name="address" rows="2" class="w-full border rounded p-2"><?php echo e(old('address', $warehouse->address)); ?></textarea>
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Phone</label>
        <input name="phone" value="<?php echo e(old('phone', $warehouse->phone)); ?>" class="w-full border rounded p-2">
    </div>

    <div class="mb-3 flex gap-4">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $warehouse->is_active) ? 'checked' : ''); ?>>
            <span>Active</span>
        </label>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_default" value="1" <?php echo e(old('is_default', $warehouse->is_default) ? 'checked' : ''); ?>>
            <span>Default Warehouse</span>
        </label>
    </div>

    <div class="flex justify-end items-center gap-3 pt-3 border-t">
        <a href="<?php echo e(route('admin.warehouses.index')); ?>" class="px-4 py-2 border border-gray-300 rounded text-gray-700">Cancel</a>
        <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
    </div>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/warehouses/edit.blade.php ENDPATH**/ ?>