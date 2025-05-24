@props(['header' => null, 'footer' => null, 'noPadding' => false])

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200']) }}>
    @if($header)
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gradient-to-r from-[var(--color-secondary)] to-[var(--color-dark-blue)]">
            <h3 class="text-lg leading-6 font-medium text-white">
                {{ $header }}
            </h3>
        </div>
    @endif

    <div class="{{ $noPadding ? '' : 'p-6' }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-4 py-4 sm:px-6 bg-gray-50 border-t border-gray-200">
            {{ $footer }}
        </div>
    @endif
</div> 