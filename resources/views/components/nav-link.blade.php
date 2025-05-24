@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-3 py-2 text-sm font-medium text-white border-b-2 border-white focus:outline-none transition duration-150 ease-in-out'
    : 'inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:border-b-2 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a> 