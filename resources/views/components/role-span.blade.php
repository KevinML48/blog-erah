@props(['role'])

<span class="font-semibold
    @if($role == 'admin')
        text-red-500
    @elseif($role == 'ultra')
        text-yellow-500
    @endif">
    {{ $slot }}
</span>
