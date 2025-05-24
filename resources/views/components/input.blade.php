@props([
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
])

<div class="w-full">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-[var(--color-accent)]">*</span>
            @endif
        </label>
    @endif

    <div class="relative rounded-md shadow-sm">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-{{ $icon }} text-gray-400"></i>
            </div>
        @endif

        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge([
                'class' => 'block w-full rounded-md ' . 
                          ($icon ? 'pl-10 ' : 'pl-3 ') .
                          'border-gray-300 focus:ring-[var(--color-secondary)] focus:border-[var(--color-secondary)] ' .
                          ($error ? 'border-red-300 text-red-900 placeholder-red-300 ' : '') .
                          ($disabled ? 'bg-gray-100 cursor-not-allowed ' : '')
            ]) }}
        >
    </div>

    @if($helper && !$error)
        <p class="mt-1 text-sm text-gray-500">{{ $helper }}</p>
    @endif

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div> 