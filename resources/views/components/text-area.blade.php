@props(['value' => ''])

<textarea
    value="{{ old('description', $value) }}"
    {{ $attributes->merge(['class' => 'border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm']) }}
>{{ old('description', $value) }}</textarea>
