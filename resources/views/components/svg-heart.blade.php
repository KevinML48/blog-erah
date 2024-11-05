@props(['filled' => false, 'hidden' => false])

<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="{{ $filled ? 'currentColor' : 'none' }}"
     stroke="{{ !$filled ? 'currentColor' : 'none' }}"
     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
     class="icon icon-tabler icons-tabler-{{ $filled ? 'filled' : 'outline' }} icon-tabler-heart {{ $hidden ? 'hidden' : '' }}">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
    @if ($filled)
        <path d="M6.979 3.074a6 6 0 0 1 4.988 1.425l.037 .033l.034 -.03a6 6 0 0 1 4.733 -1.44l.246 .036a6 6 0 0 1 3.364 10.008l-.18 .185l-.048 .041l-7.45 7.379a1 1 0 0 1 -1.313 .082l-.094 -.082l-7.493 -7.422a6 6 0 0 1 3.176 -10.215z"/>
    @else
        <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"/>
    @endif
</svg>
