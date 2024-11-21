@props(['default' => '', 'sections', 'triggers'])

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
    @foreach ($sections as $key => $content)
        <div x-show="activeSection === '{{ $key }}'" class="space-y-6">
            {{ $content }}
        </div>
    @endforeach
</div>
