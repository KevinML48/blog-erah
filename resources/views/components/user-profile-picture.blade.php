@props(['user', 'default' => true, 'size' => '12' , 'border' => true])

@php
    $class = '';
    if ($border) {
        $class = match ($user->role) {
            'admin' => 'border-2 border-red-500 shadow-lg',
            'ultra' => 'border-2 border-yellow-500 shadow-lg',
            default => 'border-2 border-gray-300',
        };
    }
@endphp

@if($user->profile_picture)
    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
         class="w-{{ $size }} h-{{ $size }} rounded-full object-cover {{ $class }}">
@elseif($default)
    <img src="{{ asset('storage/profile_pictures/default.png')}}" alt="Default Profile Picture"
         class="w-{{ $size }} h-{{ $size }} rounded-full object-cover {{ $class }}">
@endif
