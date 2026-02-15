@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200 ease-in-out focus:scale-[1.01]']) !!}>
