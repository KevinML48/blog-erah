<button {{ $attributes->merge(['type' => 'submit', 'class' => 'relative inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150', 'style' => 'background-image: linear-gradient(45deg, yellow 25%, black 25%, black 50%, yellow 50%, yellow 75%, black 75%, black 100%); background-size: 20px 20px;']) }}>
    <span class="absolute inset-0 bg-black opacity-75 rounded-md"></span>
    <span class="relative" style="text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;">
        {{ $slot }}
    </span>
</button>
