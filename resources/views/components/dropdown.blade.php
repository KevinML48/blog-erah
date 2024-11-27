@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-black', 'triggerType' => 'click', 'flex' => '', 'class' => ''])

@php
    $alignmentClasses = match ($align) {
        'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
        'top' => 'origin-top',
        'bottom' => 'top-full mt-2',
        default => 'ltr:origin-top-right rtl:origin-top-left end-0',
    };

//    $width = match($width) {
//        '48' => 'w-48',
//        'fit' => 'w-fit',
//        'full' => 'w-full',
//        'auto' => 'w-auto',
//        default => $width,
//    };

    $triggerEvents = match($triggerType) {
        'hover' => '@click.outside="open = false" @click="if (window.innerWidth < 768) { open = !open }" @mouseover="open = true" @mouseleave="open = false"',
        default => '@click="open = !open" @click.outside="open = false" @close.stop="open = false"',
    };

    $positioningClasses = match($triggerType) {
        'hover' => 'bottom-full mt-2',
        default => 'mt-2',
    };

    $flexClass = match($flex) {
        'row' => 'flex flex-row',
        'col' => 'flex flex-col',
        default => '',
    };
@endphp

<div class="relative" x-data="{ open: false }" {!! $triggerEvents !!}>
    <div>
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute z-50 {{ $positioningClasses }} w-{{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
         style="display: none;"
    >
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }} {{ $flexClass }} items-end {{ $class }}">
            {{ $content }}
        </div>
    </div>
</div>
