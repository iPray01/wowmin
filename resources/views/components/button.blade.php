@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'disabled' => false
])

@php
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
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
    @if($disabled) disabled @endif
>
    @if($icon && $iconPosition === 'left')
        <i class="fas fa-{{ $icon }} mr-2"></i>
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        <i class="fas fa-{{ $icon }} ml-2"></i>
    @endif
</button> 