@props([
    'label' => null,
    'error' => null,
    'name',
    'value' => null,
    'options' => [],
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

        <select
            name="{{ $name }}"
            id="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge([
                'class' => 'block w-full rounded-md ' . 
                          ($icon ? 'pl-10 ' : 'pl-3 ') .
                          'border-gray-300 focus:ring-[var(--color-secondary)] focus:border-[var(--color-secondary)] ' .
                          ($error ? 'border-red-300 text-red-900 ' : '') .
                          ($disabled ? 'bg-gray-100 cursor-not-allowed ' : '')
            ]) }}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif

            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ $optionValue == old($name, $value) ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>

        @if($icon)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class="fas fa-chevron-down text-gray-400"></i>
            </div>
        @endif
    </div>

    @if($helper && !$error)
        <p class="mt-1 text-sm text-gray-500">{{ $helper }}</p>
    @endif

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div> 