@props(['role', 'badge' => true])

<span class="font-semibold
    @if($role == 'admin')
        text-red-500
    @elseif($role == 'ultra')
        text-yellow-500
    @endif">
    {{ $slot }}
    @if($badge)
        @if($role == 'admin')
            <x-svg-admin />
        @endif
        @if($role == 'ultra')
            <x-svg-ultra />
        @endif
    @endif
</span>
