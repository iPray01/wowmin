<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'name',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'helper' => null,
    'icon' => null
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
    'type' => 'text',
    'label' => null,
    'error' => null,
    'name',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'helper' => null,
    'icon' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="w-full">
    <?php if($label): ?>
        <label for="<?php echo e($name); ?>" class="block text-sm font-medium text-gray-700 mb-1">
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="text-[var(--color-accent)]">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>

    <div class="relative rounded-md shadow-sm">
        <?php if($icon): ?>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-<?php echo e($icon); ?> text-gray-400"></i>
            </div>
        <?php endif; ?>

        <input
            type="<?php echo e($type); ?>"
            name="<?php echo e($name); ?>"
            id="<?php echo e($name); ?>"
            value="<?php echo e(old($name, $value)); ?>"
            placeholder="<?php echo e($placeholder); ?>"
            <?php if($required): ?> required <?php endif; ?>
            <?php if($disabled): ?> disabled <?php endif; ?>
            <?php echo e($attributes->merge([
                'class' => 'block w-full rounded-md ' . 
                          ($icon ? 'pl-10 ' : 'pl-3 ') .
                          'border-gray-300 focus:ring-[var(--color-secondary)] focus:border-[var(--color-secondary)] ' .
                          ($error ? 'border-red-300 text-red-900 placeholder-red-300 ' : '') .
                          ($disabled ? 'bg-gray-100 cursor-not-allowed ' : '')
            ])); ?>

        >
    </div>

    <?php if($helper && !$error): ?>
        <p class="mt-1 text-sm text-gray-500"><?php echo e($helper); ?></p>
    <?php endif; ?>

    <?php if($error): ?>
        <p class="mt-1 text-sm text-red-600"><?php echo e($error); ?></p>
    <?php endif; ?>
</div> <?php /**PATH C:\xampp\htdocs\wowmin\resources\views\components\input.blade.php ENDPATH**/ ?>