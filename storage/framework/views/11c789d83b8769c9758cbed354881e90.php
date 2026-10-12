<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-bold mb-4">Edit Product</h1>

<form method="POST" action="<?php echo e(route('admin.products.update', $product)); ?>" class="bg-white p-6 rounded-lg shadow max-w-xl">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <?php if(auth()->user()->hasRole('super_admin')): ?>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Company *</label>
        <select name="company_id" class="w-full border rounded p-2" required>
            <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($id); ?>" <?php echo e(old('company_id', $product->company_id) == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <?php else: ?>
    <input type="hidden" name="company_id" value="<?php echo e(auth()->user()->company_id); ?>">
    <div class="mb-3 p-3 bg-gray-100 rounded">
        <span class="text-gray-600">Company: </span>
        <strong><?php echo e(auth()->user()->company->name ?? '-'); ?></strong>
    </div>
<?php endif; ?>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Category</label>
        <select name="category_id" class="w-full border rounded p-2">
            <option value="">-- None --</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($id); ?>" <?php echo e(old('category_id', $product->category_id) == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Product Name *</label>
        <input name="name" value="<?php echo e(old('name', $product->name)); ?>" class="w-full border rounded p-2" required>
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">SKU</label>
        <input name="sku" value="<?php echo e(old('sku', $product->sku)); ?>" class="w-full border rounded p-2">
    </div>
    <div class="grid grid-cols-2 gap-3 mb-3">
        <div>
            <label class="block mb-1 font-medium">Price *</label>
            <input name="price" type="number" step="0.01" value="<?php echo e(old('price', $product->price)); ?>" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label class="block mb-1 font-medium">Stock *</label>
            <input name="stock" type="number" value="<?php echo e(old('stock', $product->stock)); ?>" class="w-full border rounded p-2" required>
        </div>
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Description</label>
        <textarea name="description" rows="3" class="w-full border rounded p-2"><?php echo e(old('description', $product->description)); ?></textarea>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    <a href="<?php echo e(route('admin.products.index')); ?>" class="ml-2 text-gray-600">Cancel</a>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>