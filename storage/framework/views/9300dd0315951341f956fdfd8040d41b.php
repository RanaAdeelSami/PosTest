<?php $__env->startSection('body-class', 'scrollable'); ?> 
<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold">Dashboard</h1>
    <p class="text-gray-600 text-sm">Welcome back, <?php echo e(auth()->user()->name); ?>!</p>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    
    <div class="bg-white p-5 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Today Sales</div>
                <div class="text-2xl font-bold mt-2 text-blue-600"><?php echo e(number_format($todaySales, 2)); ?></div>
                <?php
                    $diff = $todaySales - $yesterdaySales;
                    $pct = $yesterdaySales > 0 ? ($diff / $yesterdaySales * 100) : ($todaySales > 0 ? 100 : 0);
                ?>
                <div class="text-xs mt-1 <?php echo e($diff >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                    <?php if($diff >= 0): ?> ▲ <?php else: ?> ▼ <?php endif; ?>
                    <?php echo e(number_format(abs($pct), 1)); ?>% vs yesterday
                </div>
            </div>
            <div class="text-3xl text-blue-200">💰</div>
        </div>
    </div>

    
    <div class="bg-white p-5 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">This Month</div>
                <div class="text-2xl font-bold mt-2 text-green-600"><?php echo e(number_format($monthSales, 2)); ?></div>
                <div class="text-xs mt-1 text-gray-500"><?php echo e(now()->format('F Y')); ?></div>
            </div>
            <div class="text-3xl text-green-200">📈</div>
        </div>
    </div>

    
    <div class="bg-white p-5 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Low Stock</div>
                <div class="text-2xl font-bold mt-2 <?php echo e($lowStockCount > 0 ? 'text-yellow-600' : 'text-gray-600'); ?>">
                    <?php echo e($lowStockCount); ?>

                </div>
                <div class="text-xs mt-1 text-gray-500">
                    <?php echo e($outOfStockCount); ?> out of stock
                </div>
            </div>
            <div class="text-3xl text-yellow-200">⚠️</div>
        </div>
    </div>

    
    <div class="bg-white p-5 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Supplier Dues</div>
                <div class="text-2xl font-bold mt-2 <?php echo e($pendingDues > 0 ? 'text-red-600' : 'text-gray-600'); ?>">
                    <?php echo e(number_format($pendingDues, 2)); ?>

                </div>
                <div class="text-xs mt-1 text-gray-500">Total payable</div>
            </div>
            <div class="text-3xl text-red-200">🧾</div>
        </div>
    </div>
</div>


<div class="bg-white p-5 rounded-lg shadow mb-6">
    <h2 class="text-lg font-bold mb-4">Last 7 Days Sales</h2>

    <?php
        $maxTrend = collect($salesTrend)->max('total') ?: 1;
    ?>

    <div class="flex items-end justify-between gap-2 h-48">
        <?php $__currentLoopData = $salesTrend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex-1 flex flex-col items-center">
                <div class="text-xs text-gray-600 mb-1 font-medium">
                    <?php echo e(number_format($day['total'], 0)); ?>

                </div>
                <div class="w-full bg-blue-500 rounded-t hover:bg-blue-600 transition-all"
                     style="height: <?php echo e($maxTrend > 0 ? max(5, ($day['total'] / $maxTrend) * 150) : 5); ?>px; min-height: 5px;"
                     title="<?php echo e($day['date']); ?>: <?php echo e(number_format($day['total'], 2)); ?>">
                </div>
                <div class="text-xs text-gray-500 mt-2"><?php echo e($day['day']); ?></div>
                <div class="text-xs text-gray-400"><?php echo e($day['date']); ?></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

    
    <div class="bg-white p-5 rounded-lg shadow">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-bold">Recent Sales</h2>
            <a href="<?php echo e(route('admin.sales.index')); ?>" class="text-blue-600 text-sm">View All →</a>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex justify-between items-center py-2 border-b last:border-0">
                <div>
                    <a href="<?php echo e(route('admin.sales.show', $sale)); ?>" class="text-blue-600 font-mono text-xs">
                        <?php echo e($sale->invoice_number); ?>

                    </a>
                    <div class="text-xs text-gray-500">
                        <?php echo e($sale->customer_name ?? 'Walk-in'); ?> • <?php echo e($sale->created_at->diffForHumans()); ?>

                    </div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-sm"><?php echo e(number_format($sale->total, 2)); ?></div>
                    <div class="text-xs text-gray-500"><?php echo e($sale->branch->name ?? '-'); ?></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-gray-500 py-4 text-sm">No sales yet.</p>
        <?php endif; ?>
    </div>

    
    <div class="bg-white p-5 rounded-lg shadow">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-bold">Top Products (This Month)</h2>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="text-blue-600 text-sm">Products →</a>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex justify-between items-center py-2 border-b last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                        <?php echo e($index + 1); ?>

                    </div>
                    <div>
                        <div class="text-sm font-medium"><?php echo e($product->product_name); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e($product->total_qty); ?> sold</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-sm"><?php echo e(number_format($product->total_amount, 2)); ?></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-gray-500 py-4 text-sm">No sales this month.</p>
        <?php endif; ?>
    </div>
</div>


<div class="bg-white p-5 rounded-lg shadow">
    <h2 class="text-lg font-bold mb-4">System Overview</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <?php if(auth()->user()->hasRole('super_admin')): ?>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600"><?php echo e($stats['companies']); ?></div>
                <div class="text-xs text-gray-500 uppercase mt-1">Companies</div>
            </div>
        <?php endif; ?>

        <div class="text-center">
            <div class="text-2xl font-bold text-green-600"><?php echo e($stats['branches']); ?></div>
            <div class="text-xs text-gray-500 uppercase mt-1">Branches</div>
        </div>

        <div class="text-center">
            <div class="text-2xl font-bold text-purple-600"><?php echo e($stats['users']); ?></div>
            <div class="text-xs text-gray-500 uppercase mt-1">Users</div>
        </div>

        <div class="text-center">
            <div class="text-2xl font-bold text-orange-600"><?php echo e($stats['products']); ?></div>
            <div class="text-xs text-gray-500 uppercase mt-1">Products</div>
        </div>

        <div class="text-center">
            <div class="text-2xl font-bold text-pink-600"><?php echo e($stats['categories']); ?></div>
            <div class="text-xs text-gray-500 uppercase mt-1">Categories</div>
        </div>

        <div class="text-center">
            <div class="text-2xl font-bold text-indigo-600"><?php echo e($totalSalesCount); ?></div>
            <div class="text-xs text-gray-500 uppercase mt-1">Total Sales</div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/dashboard.blade.php ENDPATH**/ ?>