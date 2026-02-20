@props(['active'])

@php
$classes = ($active ?? false)
            ? 'px-3.5 py-2 text-sm font-semibold bg-primary text-white rounded-xl shadow-md shadow-primary/25'
            : 'px-3.5 py-2 text-sm font-semibold text-gray-600 hover:text-primary hover:bg-primary/5 rounded-xl';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
