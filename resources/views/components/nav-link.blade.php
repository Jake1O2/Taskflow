@props(['active'])

@php
$classes = ($active ?? false)
            ? 'px-4 py-2 text-sm font-bold bg-primary text-white rounded-xl shadow-lg shadow-primary/20 hover:scale-105 transition-all'
            : 'px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-900 hover:bg-gray-100/50 rounded-xl transition-all';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
