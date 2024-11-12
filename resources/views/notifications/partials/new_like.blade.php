<div class="new-like-notification py-2">
    <div class="py-2 flex items-center">
        <div class="text-red-600">
            <x-svg-heart :filled="true"/>
        </div>
        <div class="ml-2 flex">
            @foreach($likes as $like)
                <img src="{{ asset('storage/' . ($like->user->profile_picture ?: 'profile_pictures/default.png')) }}"
                     alt="{{ $like->user->name }}'s Profile Picture"
                     class="w-8 h-8 rounded-full object-cover">
            @endforeach
        </div>
    </div>
    <div class="ml-8 py-2">
        @php
            // Collect the users' names and roles
            $likeUsers = $likes->map(function($like) {
                return [
                    'name' => $like->user->name,
                    'role' => $like->user->role,
                    'profile_link' => route('profile.show', $like->user->name)
                ];
            });
            $likeCount = $likeUsers->count();
        @endphp

        @foreach($likeUsers as $index => $user)
            <x-role-span :role="$user['role']">
                <a href="{{ $user['profile_link'] }}" class="font-bold text-left focus:outline-none">
                    {{ $user['name'] }}
                </a>
            </x-role-span>
            @if ($index === $likeCount - 2)
                et
            @elseif ($index !== $likeCount - 1)
                ,
            @endif
        @endforeach

        {{-- Correct pluralization --}}
        @if ($likeCount === 1)
            a liké votre commentaire.
        @elseif ($likeCount > 1)
            ont liké votre commentaire.
        @endif

        {{-- Display the comment content --}}
        @include('posts.partials.comment-content', ['content' => $likes->first()->likeable, 'showMedia' => false])
    </div>
</div>
