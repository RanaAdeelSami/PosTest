<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow">
    <div class="flex justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Supplier Return</h1>
            <p class="font-mono text-sm"><?php echo e($supplierReturn->return_number); ?></p>
            <p class="text-sm"><?php echo e($supplierReturn->return_date->format('d M Y')); ?></p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-600">Supplier:</p>
            <p class="font-bold"><?php echo e($supplierReturn->supplier->name ?? '-'); ?></p>
            <p class="text-sm"><?php echo e($supplierReturn->supplier->phone ?? ''); ?></p>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
        <div>
            <p><strong>Branch:</strong> <?php echo e($supplierReturn->branch->name ?? '-'); ?></p>
            <p><strong>Settlement:</strong> <?php echo e(ucfirst(str_replace('_', ' ', $supplierReturn->settlement))); ?></p>
            <p><strong>Reason:</strong> <?php echo e($supplierReturn->reason ?? '-'); ?></p>
        </div>
        <div class="text-right">
            <p><strong>Processed by:</strong> <?php echo e($supplierReturn->user->name ?? '-'); ?></p>
            <?php if($supplierReturn->purchase): ?>
                <p><strong>Linked Purchase:</strong> <?php echo e($supplierReturn->purchase->purchase_number); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <table class="w-full mb-4 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Product</th>
                <th class="p-2 text-right">Unit Cost</th>
                <th class="p-2 text-right">Qty</th>
                <th class="p-2 text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $supplierReturn->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-t">
                    <td class="p-2"><?php echo e($item->product_name); ?></td>
                    <td class="p-2 text-right"><?php echo e(number_format($item->unit_cost, 2)); ?></td>
                    <td class="p-2 text-right"><?php echo e($item->quantity); ?></td>
                    <td class="p-2 text-right"><?php echo e(number_format($item->total, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <div class="text-right border-t pt-3">
        <span class="font-bold text-lg">Total: </span>
        <span class="font-bold text-lg text-red-600"><?php echo e(number_format($supplierReturn->total, 2)); ?></span>
    </div>
    <?php if($supplierReturn->notes): ?>
        <div class="mt-4 p-3 bg-gray-50 rounded text-sm"><strong>Notes:</strong> <?php echo e($supplierReturn->notes); ?></div>
    <?php endif; ?>
    <div class="mt-6 flex gap-2">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded">Print</button>
        <a href="<?php echo e(route('admin.supplier-returns.index')); ?>" class="bg-gray-200 px-4 py-2 rounded">All Returns</a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/supplier_returns/show.blade.php ENDPATH**/ ?>