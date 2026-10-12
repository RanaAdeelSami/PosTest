

<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold"><?php echo e($warehouse->name); ?></h1>
        <p class="text-sm text-gray-500">
            <?php echo e($warehouse->code); ?> | Branch: <?php echo e($warehouse->branch->name ?? '-'); ?>

            <?php if($warehouse->is_default): ?>
                <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs ml-2">DEFAULT</span>
            <?php endif; ?>
        </p>
    </div>
    <a href="<?php echo e(route('admin.warehouses.index')); ?>" class="text-blue-600">← All Warehouses</a>
</div>


<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Products</div>
        <div class="text-2xl font-bold"><?php echo e($warehouse->stocks->count()); ?></div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Total Stock</div>
        <div class="text-2xl font-bold"><?php echo e($totalStock); ?></div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Stock Value</div>
        <div class="text-2xl font-bold text-blue-600"><?php echo e(number_format($totalValue, 2)); ?></div>
    </div>
</div>


<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">Product</th>
                <th class="p-3 text-left">SKU</th>
                <th class="p-3 text-right">Quantity</th>
                <th class="p-3 text-right">Price</th>
                <th class="p-3 text-right">Value</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $warehouse->stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-t">
                    <td class="p-3"><?php echo e($stock->product->name ?? 'N/A'); ?></td>
                    <td class="p-3 font-mono text-xs"><?php echo e($stock->product->sku ?? '-'); ?></td>
                    <td class="p-3 text-right font-bold"><?php echo e($stock->quantity); ?></td>
                    <td class="p-3 text-right"><?php echo e(number_format($stock->product->price ?? 0, 2)); ?></td>
                    <td class="p-3 text-right"><?php echo e(number_format(($stock->quantity) * ($stock->product->price ?? 0), 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="p-6 text-center text-gray-500">No stock in this warehouse.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/warehouses/show.blade.php ENDPATH**/ ?>