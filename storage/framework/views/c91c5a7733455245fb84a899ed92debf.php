<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id',
    'title' => 'Modal',
    'maxWidth' => '480px',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'id',
    'title' => 'Modal',
    'maxWidth' => '480px',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div id="<?php echo e($id); ?>" style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;display:none;align-items:center;justify-content:center;padding:20px;">
    <div style="background:white;border-radius:14px;width:100%;max-width:<?php echo e($maxWidth); ?>;max-height:90vh;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 20px 50px rgba(0,0,0,0.2);">

        
        <div style="padding:20px 24px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:18px;font-weight:700;color:#1a1a1a;"><?php echo e($title); ?></h2>
            <button type="button" onclick="closeModal('<?php echo e($id); ?>')"
                    style="background:#dc2626;border:none;color:white;width:28px;height:28px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:bold;">
                ×
            </button>
        </div>

        
        <div style="padding:20px 24px;overflow-y:auto;flex:1;">
            <?php echo e($slot); ?>

        </div>

    </div>
</div><?php /**PATH C:\laragon\www\Laravel\PosTest\resources\views/components/modal.blade.php ENDPATH**/ ?>