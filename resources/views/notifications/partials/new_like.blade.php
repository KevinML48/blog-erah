<div class="new-like-notification py-2">
    <div class="py-2 flex items-center">
        <div class="text-red-600">
            <x-svg-heart :filled="true"/>
        </div>
        <div class="ml-2 flex">
            @foreach($likes as $like)
                @if($like->user->profile_picture)
                    <img src="{{ asset('storage/' . $like->user->profile_picture) }}" alt="Profile Picture"
                         class="w-8 h-8 rounded-full object-cover">
                @else
                    <img src="{{ asset('storage/profile_pictures/default.png')}}" alt="Default Profile Picture"
                         class="w-8 h-8 rounded-full object-cover">
                @endif
            @endforeach
        </div>
    </div>
    <div class="ml-8 py-2">
        @php
            // Collect the names of users who liked the comment
            $likeUsers = $likes->pluck('user.name')->toArray();
            $likeCount = count($likeUsers);
        @endphp

        @if ($likeCount === 1)
            {{-- Single like --}}
            <a href="{{ route('profile.show', $likeUsers[0]) }}" class="erah-link font-bold text-left focus:outline-none">
                {{ $likeUsers[0] }}
            </a>
            a liké votre commentaire.
        @elseif ($likeCount === 2)
            {{-- Two likes --}}
            <a href="{{ route('profile.show', $likeUsers[0]) }}" class="erah-link font-bold text-left focus:outline-none">
                {{ $likeUsers[0] }}
            </a> et
            <a href="{{ route('profile.show', $likeUsers[1]) }}" class="erah-link font-bold text-left focus:outline-none">
                {{ $likeUsers[1] }}
            </a>
            ont liké votre commentaire.
        @elseif ($likeCount === 3)
            {{-- Three likes --}}
            <a href="{{ route('profile.show', $likeUsers[0]) }}" class="erah-link font-bold text-left focus:outline-none">
                {{ $likeUsers[0] }}
            </a>,
            <a href="{{ route('profile.show', $likeUsers[1]) }}" class="erah-link font-bold text-left focus:outline-none">
                {{ $likeUsers[1] }}
            </a>, et
            <a href="{{ route('profile.show', $likeUsers[2]) }}" class="erah-link font-bold text-left focus:outline-none">
                {{ $likeUsers[2] }}
            </a>
            ont liké votre commentaire.
        @else
            {{-- More than three likes --}}
            <a href="{{ route('profile.show', $likeUsers[0]) }}" class="erah-link font-bold text-left focus:outline-none">
                {{ $likeUsers[0] }}
            </a>,
            <a href="{{ route('profile.show', $likeUsers[1]) }}" class="erah-link font-bold text-left focus:outline-none">
                {{ $likeUsers[1] }}
            </a>,
            <a href="{{ route('profile.show', $likeUsers[2]) }}" class="erah-link font-bold text-left focus:outline-none">
                {{ $likeUsers[2] }}
            </a>, et {{ $count - $likeCount }} autres ont liké votre commentaire.
        @endif

        {{-- Display the comment content --}}
        <p>{{ $likes->first()->likeable->body }}</p>
    </div>

</div>
