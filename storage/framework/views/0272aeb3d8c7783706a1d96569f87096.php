<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'disabled' => false
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
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'disabled' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-md focus:outline-none transition-colors duration-200';
    
    $variants = [
        'primary' => 'bg-[var(--color-secondary)] hover:bg-[var(--color-dark-blue)] text-white',
        'secondary' => 'bg-[var(--color-primary)] hover:bg-[var(--color-slate)] text-white',
        'danger' => 'bg-[var(--color-accent)] hover:bg-red-700 text-white',
        'outline' => 'border border-[var(--color-secondary)] text-[var(--color-secondary)] hover:bg-[var(--color-secondary)] hover:text-white',
        'ghost' => 'text-[var(--color-secondary)] hover:bg-[var(--color-light-teal)]'
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg'
    ];
    
    $classes = $baseClasses . ' ' . 
              ($variants[$variant] ?? $variants['primary']) . ' ' . 
              ($sizes[$size] ?? $sizes['md']) . ' ' .
              ($disabled ? 'opacity-50 cursor-not-allowed' : '');
?>

<button 
    type="<?php echo e($type); ?>"
    <?php echo e($attributes->merge(['class' => $classes])); ?>

    <?php if($disabled): ?> disabled <?php endif; ?>
>
    <?php if($icon && $iconPosition === 'left'): ?>
        <i class="fas fa-<?php echo e($icon); ?> mr-2"></i>
    <?php endif; ?>
    
    <?php echo e($slot); ?>

    
    <?php if($icon && $iconPosition === 'right'): ?>
        <i class="fas fa-<?php echo e($icon); ?> ml-2"></i>
    <?php endif; ?>
</button> <?php /**PATH C:\xampp\htdocs\wowmin\resources\views\components\button.blade.php ENDPATH**/ ?>