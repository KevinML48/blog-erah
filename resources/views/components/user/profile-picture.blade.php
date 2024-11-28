@props(['user', 'default' => true, 'size' => '12' , 'border' => true, 'card' => false])

@php
    $class = '';
    if ($border) {
        $class = match ($user->role) {
            'admin' => 'border-2 border-red-500 shadow-lg',
            'ultra' => 'border-2 border-yellow-500 shadow-lg',
            default => 'border-2 border-gray-300',
        };
    }

    $roleColors = [
    'admin' => 'red-600',
    'ultra' => 'yellow-500',
];

$color = $roleColors[$user->role] ?? 'white';
@endphp

@if($card)
    <x-dropdown triggerType="hover" align="bottom" width="auto" class="border border-{{ $color }} shadow-md hover:scale-105">
        <x-slot name="trigger">
            @if($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
                     class="w-{{ $size }} h-{{ $size }} rounded-full object-cover {{ $class }}">
            @elseif($default)
                <img src="{{ asset('storage/profile_pictures/default.png')}}" alt="Default Profile Picture"
                     class="w-{{ $size }} h-{{ $size }} rounded-full object-cover {{ $class }}">
            @endif
        </x-slot>
        <x-slot name="content">
            <x-user.card :user="$user"/>
        </x-slot>
    </x-dropdown>
@else
    @if($user->profile_picture)
        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
             class="w-{{ $size }} h-{{ $size }} rounded-full object-cover {{ $class }}">
    @elseif($default)
        <img src="{{ asset('storage/profile_pictures/default.png')}}" alt="Default Profile Picture"
             class="w-{{ $size }} h-{{ $size }} rounded-full object-cover {{ $class }}">
    @endif
@endif
