@props(['default' => '', 'sections', 'triggers', 'scroll' => false, 'functions' => [], 'functionsAttribute' => ''])

@php
    $scroll = match ($scroll) {
        true => 'h-[calc(100vh-40rem)] overflow-y-auto',
        default => '',
    };
@endphp

<div x-data="{
    activeSection: '{{ $default }}',
    functions: @js($functions),  // Make sure this is properly passed as a JS array
    username: '{{ $functionsAttribute }}',  // Add username as a variable
    checkScrollToBottom(sectionName) {
        const section = this.$refs[`section${sectionName}`];
        if (section.scrollHeight - section.scrollTop === section.clientHeight) {
            console.log(`Scrolled to the bottom of the section: ${sectionName}`);
            // Trigger the corresponding function if it exists
            const functionName = this.functions[sectionName];
            if (functionName && typeof window[functionName] === 'function') {
                // Pass the username to the function
                window[functionName](this.username);
            }
        }
    }
}">
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
                <div
                    class="h-full overflow-y-auto"
                    x-ref="section{{ $key }}"
                    @scroll="checkScrollToBottom('{{ $key }}')">
                    {{ $content }}
                </div>
            </div>
        @endforeach
    </div>
</div>
