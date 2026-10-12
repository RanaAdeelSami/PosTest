<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h1 class="text-2xl font-bold">Inventory Report</h1>
</div>


<div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase tracking-wide">Products</div>
        <div class="text-2xl font-bold"><?php echo e($summary['total_products']); ?></div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase tracking-wide">Total Stock</div>
        <div class="text-2xl font-bold"><?php echo e($summary['total_stock']); ?></div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase tracking-wide">Out of Stock</div>
        <div class="text-2xl font-bold text-red-600"><?php echo e($summary['out_of_stock']); ?></div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase tracking-wide">Low Stock</div>
        <div class="text-2xl font-bold text-yellow-600"><?php echo e($summary['low_stock']); ?></div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase tracking-wide">Stock Value</div>
        <div class="text-2xl font-bold text-blue-600"><?php echo e(number_format($summary['stock_value'], 2)); ?></div>
    </div>
</div>


<form method="GET" id="filterForm" class="bg-white p-4 rounded-lg shadow mb-4 flex flex-wrap gap-3 items-end">

    <div>
        <label class="block text-xs text-gray-500 mb-1">Search</label>
        <input name="search" id="searchInput" value="<?php echo e(request('search')); ?>"
               placeholder="Name / SKU"
               class="border rounded p-2 w-48">
    </div>

    <div>
        <label class="block text-xs text-gray-500 mb-1">Category</label>
        <select name="category_id" class="border rounded p-2">
            <option value="">All Categories</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($id); ?>" <?php echo e(request('category_id') == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div>
        <label class="block text-xs text-gray-500 mb-1">Stock Status</label>
        <select name="stock_status" class="border rounded p-2">
            <option value="">All</option>
            <option value="out" <?php echo e(request('stock_status') == 'out' ? 'selected' : ''); ?>>Out of Stock</option>
            <option value="low" <?php echo e(request('stock_status') == 'low' ? 'selected' : ''); ?>>Low (1-10)</option>
            <option value="ok" <?php echo e(request('stock_status') == 'ok' ? 'selected' : ''); ?>>OK (>10)</option>
        </select>
    </div>

    <div>
        <label class="block text-xs text-gray-500 mb-1">Sort By</label>
        <select name="sort" class="border rounded p-2">
            <option value="name_asc" <?php echo e(request('sort') == 'name_asc' ? 'selected' : ''); ?>>Name (A-Z)</option>
            <option value="name_desc" <?php echo e(request('sort') == 'name_desc' ? 'selected' : ''); ?>>Name (Z-A)</option>
            <option value="stock_asc" <?php echo e(request('sort') == 'stock_asc' ? 'selected' : ''); ?>>Stock (Low → High)</option>
            <option value="stock_desc" <?php echo e(request('sort') == 'stock_desc' ? 'selected' : ''); ?>>Stock (High → Low)</option>
            <option value="value_asc" <?php echo e(request('sort') == 'value_asc' ? 'selected' : ''); ?>>Value (Low → High)</option>
            <option value="value_desc" <?php echo e(request('sort') == 'value_desc' ? 'selected' : ''); ?>>Value (High → Low)</option>
        </select>
    </div>

    <?php if(request()->hasAny(['search', 'category_id', 'stock_status', 'sort'])): ?>
        <a href="<?php echo e(route('admin.inventory.report')); ?>" class="text-gray-600 px-3 py-2 text-sm hover:text-gray-800">✕ Clear</a>
    <?php endif; ?>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export.inventory')): ?>
        <div class="flex gap-2 ml-auto">
            <a href="<?php echo e(route('admin.export.inventory', ['format' => 'xlsx'] + request()->query())); ?>"
               class="bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 whitespace-nowrap">📊 Excel</a>
            <a href="<?php echo e(route('admin.export.inventory', ['format' => 'pdf'] + request()->query())); ?>"
               class="bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700 whitespace-nowrap">📄 PDF</a>
        </div>
    <?php endif; ?>
</form>


<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">Product</th>
                <th class="p-3 text-left">SKU</th>
                <th class="p-3 text-left">Category</th>
                <th class="p-3 text-right">Cost</th>
                <th class="p-3 text-right">Price</th>
                <th class="p-3 text-right">Stock</th>
                <th class="p-3 text-right">Value</th>
                <th class="p-3 text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3 font-medium"><?php echo e($p->name); ?></td>
                    <td class="p-3 font-mono text-xs text-gray-500"><?php echo e($p->sku ?? '-'); ?></td>
                    <td class="p-3"><?php echo e($p->category->name ?? '-'); ?></td>
                    <td class="p-3 text-right text-gray-600"><?php echo e(number_format($p->cost_price, 2)); ?></td>
                    <td class="p-3 text-right"><?php echo e(number_format($p->price, 2)); ?></td>
                    <td class="p-3 text-right font-bold <?php echo e($p->stock <= 0 ? 'text-red-600' : ($p->stock <= 10 ? 'text-yellow-600' : 'text-green-600')); ?>">
                        <?php echo e($p->stock); ?>

                    </td>
                    <td class="p-3 text-right"><?php echo e(number_format($p->stock * $p->price, 2)); ?></td>
                    <td class="p-3 text-center">
                        <?php if($p->stock <= 0): ?>
                            <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-bold">OUT</span>
                        <?php elseif($p->stock <= 10): ?>
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs font-bold">LOW</span>
                        <?php else: ?>
                            <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs">OK</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="p-6 text-center text-gray-500">
                        <?php if(request()->hasAny(['search', 'category_id', 'stock_status'])): ?>
                            No products match your filters.
                            <a href="<?php echo e(route('admin.inventory.report')); ?>" class="text-blue-600">Clear filters</a>
                        <?php else: ?>
                            No products found.
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-4"><?php echo e($products->appends(request()->query())->links()); ?></div>

<?php if($products->count() > 0): ?>
    <div class="mt-2 text-xs text-gray-500 text-right">
        Showing <?php echo e($products->firstItem()); ?>–<?php echo e($products->lastItem()); ?> of <?php echo e($products->total()); ?> products
        <?php if(request()->hasAny(['search', 'category_id', 'stock_status'])): ?>
            (filtered)
        <?php endif; ?>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('filterForm');
    if (!form) return;

    form.querySelectorAll('select, input[type="date"]').forEach(function(el) {
        el.addEventListener('change', function() { form.submit(); });
    });

    var searchInput = document.getElementById('searchInput');
    if (searchInput) {
        var timer = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(function() { form.submit(); }, 500);
        });
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(timer);
                form.submit();
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/admin/inventory/report.blade.php ENDPATH**/ ?>