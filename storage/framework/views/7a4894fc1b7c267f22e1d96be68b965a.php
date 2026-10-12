<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Companies</h1>
    <a href="<?php echo e(route('admin.companies.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ New Company</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Phone</th>
                <th class="p-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-t">
                    <td class="p-3"><?php echo e($company->name); ?></td>
                    <td class="p-3"><?php echo e($company->email); ?></td>
                    <td class="p-3"><?php echo e($company->phone); ?></td>
                    <td class="p-3 text-right">
                        <a href="<?php echo e(route('admin.companies.edit', $company)); ?>" class="text-blue-600">Edit</a>
                        <form method="POST" action="<?php echo e(route('admin.companies.destroy', $company)); ?>" class="inline" onsubmit="return confirm('Delete?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="text-red-600 ml-3">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="p-6 text-center text-gray-500">No companies yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-4"><?php echo e($companies->links()); ?></div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/companies/index.blade.php ENDPATH**/ ?>