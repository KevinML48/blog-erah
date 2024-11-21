@props(['trigger' => '', 'label'])

<div
    @click="activeSection = '{{ $trigger }}'"
    :class="{ 'ring-2 ring-white ring-offset-2': activeSection === '{{ $trigger }}' }"
    class="erah-button cursor-pointer px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none transition duration-300 ease-in-out">
    {{ $label }}
</div>
