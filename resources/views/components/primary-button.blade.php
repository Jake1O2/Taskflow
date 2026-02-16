<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gray-900 border border-transparent rounded-2xl font-bold text-sm text-white uppercase tracking-widest hover:bg-black hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-900/10 transition-all shadow-xl shadow-gray-900/10']) }}>
    {{ $slot }}
</button>
