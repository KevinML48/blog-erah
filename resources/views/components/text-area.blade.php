@props(['disabled' => false])

<textarea @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm']) }}>{{ $slot }}</textarea>
