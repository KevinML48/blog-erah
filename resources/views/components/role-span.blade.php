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
            <x-badge-admin />
        @endif
        @if($role == 'ultra')
            <x-badge-ultra />
        @endif
    @endif
</span>
