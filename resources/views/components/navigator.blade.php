@props(['default' => '', 'sections', 'triggers', 'scroll' => false, 'functions' => []])

@php
    $scroll = match ($scroll) {
        true => 'md:h-[calc(100vh-40rem)] h-[calc(100vh-7rem)] overflow-y-auto',
        default => '',
    };
@endphp

<div x-data="{
    activeSection: '{{ $default }}',
    functions: @js($functions),
    runOnceFlags: {
        @foreach ($functions as $key => $function)
            '{{ $key }}': {{ isset($function['runOnce']) && $function['runOnce'] ? 'false' : 'true' }},
        @endforeach
    },
    checkScrollToBottom(sectionName) {
        const section = this.$refs[`section${sectionName}`];
        if (section.scrollHeight - section.scrollTop === section.clientHeight) {
            const { functionName, attributes } = this.functions[sectionName] || {};
            if (functionName && typeof window[functionName] === 'function') {
                window[functionName](...(attributes || []));
            }
        }
    },
    triggerClick(sectionName) {
        const { functionName, attributes, runOnce } = this.functions[sectionName] || {};
        if (runOnce && !this.runOnceFlags[sectionName]) {
            if (functionName && typeof window[functionName] === 'function') {
                window[functionName](...(attributes || []));
                this.runOnceFlags[sectionName] = true;
            }
        }
    }
}">
    <div class="flex space-x-2 mb-4">
        @foreach ($triggers as $key => $trigger)
            <div
                @click="activeSection = '{{ $key }}'; triggerClick('{{ $key }}')"
                :class="{ 'ring-2 ring-white ring-offset-2': activeSection === '{{ $key }}' }"
                class="cursor-pointer">
                {{ $trigger }}
            </div>
        @endforeach
    </div>

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
