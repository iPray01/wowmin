<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['header' => null, 'footer' => null, 'noPadding' => false]));

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

foreach (array_filter((['header' => null, 'footer' => null, 'noPadding' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div <?php echo e($attributes->merge(['class' => 'bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200'])); ?>>
    <?php if($header): ?>
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gradient-to-r from-[var(--color-secondary)] to-[var(--color-dark-blue)]">
            <h3 class="text-lg leading-6 font-medium text-white">
                <?php echo e($header); ?>

            </h3>
        </div>
    <?php endif; ?>

    <div class="<?php echo e($noPadding ? '' : 'p-6'); ?>">
        <?php echo e($slot); ?>

    </div>

    <?php if($footer): ?>
        <div class="px-4 py-4 sm:px-6 bg-gray-50 border-t border-gray-200">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?>
</div> <?php /**PATH C:\xampp\htdocs\wowmin\resources\views\components\card.blade.php ENDPATH**/ ?>