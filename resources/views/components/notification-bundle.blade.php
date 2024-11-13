@props(['type', 'list', 'count'])

{{--Map the variables--}}
@php
    $userList = $list->map(function($element) use ($type) {
        $user = $type === 'follow' ? $element->follower : $element->user;

        return [
            'name' => $user->name,
            'role' => $user->role,
            'profile_picture' => $user->profile_picture ?: 'profile_pictures/default.png',
            'profile_link' => route('profile.show', $user->name)
        ];
    });
@endphp

<div class="new-{{ $type }}-notification py-2">
    <div class="py-2 flex items-center">
        {{-- Icon --}}
        <div class="text-red-600">
            @if ($type === 'like')
                <x-svg-heart :filled="true"/>
            @elseif ($type === 'follow')
                <x-svg-follow/>
            @endif
        </div>
        {{-- Profile Pictures --}}
        <div class="ml-2 flex">
            @foreach($userList as $user)
                <img src="{{ asset('storage/' . $user['profile_picture']) }}"
                     alt="{{ $user['name'] }}'s Profile Picture"
                     class="w-8 h-8 rounded-full object-cover">
            @endforeach
        </div>
    </div>
    {{-- Usernames --}}
    <div class="ml-8 py-2">
        {{-- If more than 3 likes or follows --}}
        @if ($count > 3)
            {{-- Take the 3 first users --}}
            @foreach($userList->take(3) as $index => $user)
                <x-role-span :role="$user['role']">
                    <a href="{{ $user['profile_link'] }}" class="font-bold text-left focus:outline-none">
                        {{ $user['name'] }}
                    </a>
                </x-role-span>
                {{-- If the current index is not 2 (i.e., not the third and last user), use a comma --}}
                @if ($index !== 2)
                    ,
                @endif
            @endforeach
            {{-- Count the remaining elements --}}
            et <strong>{{ $count - 3 }}</strong> autres
        @else
            {{-- If 3 or fewer likes or follows --}}
            @foreach($userList as $index => $user)
                <x-role-span :role="$user['role']">
                    <a href="{{ $user['profile_link'] }}" class="font-bold text-left focus:outline-none">
                        {{ $user['name'] }}
                    </a>
                </x-role-span>
                {{-- If the index is the second-to-last user, insert "et" --}}
                @if ($index === $count - 2)
                    et
                    {{-- If index is not the last, use a comma --}}
                @elseif ($index !== $count - 1)
                    ,
                @endif
            @endforeach
        @endif


        {{-- Correct pluralization based on type and count --}}
        @if ($count === 1)
            @if ($type === 'like')
                a aimé votre commentaire.
            @elseif ($type === 'follow')
                vous a suivi.
            @endif
        @elseif ($count > 1)
            @if ($type === 'like')
                ont aimé votre commentaire.
            @elseif ($type === 'follow')
                vous ont suivi.
            @endif
        @endif

        @if($type === 'like')
            @include('posts.partials.comment-content', ['content' => $list->first()->likeable, 'showMedia' => false])
        @endif
    </div>
</div>
