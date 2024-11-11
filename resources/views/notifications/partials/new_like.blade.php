<div class="new-like-notification py-2">
    <div class="py-2 flex items-center">
        <div class="text-red-600">
            <x-svg-heart :filled="true"/>
        </div>
        <div class="ml-2">
            @if($like->user->profile_picture)
                <img src="{{ asset('storage/' . $like->user->profile_picture) }}" alt="Profile Picture"
                     class="w-8 h-8 rounded-full object-cover">
            @else
                <img src="{{ asset('storage/profile_pictures/default.png')}}" alt="Default Profile Picture"
                     class="w-8 h-8 rounded-full object-cover">
            @endif
        </div>
    </div>
    <div class="ml-8 py-2">
        <a href="{{ route('profile.show', $like->user->name) }}"
           class="erah-link font-bold text-left focus:outline-none">
            <x-role-span :role="$like->user->role">
                {{ $like->user->name }}
            </x-role-span>
        </a> a liké votre commentaire
        <p>{{ $like->likeable->body }}</p>
    </div>
</div>
