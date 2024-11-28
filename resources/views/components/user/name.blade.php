@props(['user', 'badge' => true])

@php
    $class = match ($user->role) {
        'admin' => 'text-red-500',
        'ultra' => 'text-yellow-500',
        default => '',
    };
@endphp
<a href="{{ route('profile.show', ['username' => $user->username]) }}"
   class="hover:underline font-semibold {{ $class }}">
    {{ $user->name }}
</a>
@if($badge)
    @if($user->role == 'admin')
        <x-user.badge.admin/>
    @endif
    @if($user->role == 'ultra')
        <x-user.badge.ultra/>
    @endif
@endif
