@props(['default' => '', 'sections', 'triggers', 'scroll' => false])

@php
    $scroll = match ($scroll) {
        true => 'h-[calc(100vh-35rem)] overflow-y-auto',
        default => '',
    };
@endphp

<div x-data="{ activeSection: '{{ $default }}' }">
    <!-- Navigation Buttons -->
    <div class="flex space-x-2 mb-4">
        @foreach ($triggers as $key => $trigger)
            <div
                @click="activeSection = '{{ $key }}'"
                :class="{ 'ring-2 ring-white ring-offset-2': activeSection === '{{ $key }}' }"
                class="cursor-pointer">
                {{ $trigger }}
            </div>
        @endforeach
    </div>

    <!-- Sections -->
    <div>
        @foreach ($sections as $key => $content)
            <div x-show="activeSection === '{{ $key }}'" class="space-y-6 {{ $scroll }}">
                <div class="h-full overflow-y-auto">
                    {{ $content }}
                </div>
            </div>
        @endforeach
    </div>
</div>
