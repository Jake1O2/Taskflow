@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-none bg-gray-100/50 focus:bg-white focus:ring-4 focus:ring-primary/10 rounded-2xl py-3 px-4 text-gray-900 placeholder-gray-400 text-sm transition-all shadow-inner']) !!}>
