<div class="new-follow-notification py-2">
    <div class="py-2 flex items-center">
        <div class="">
            <x-svg-follow/>
        </div>
        <div class="ml-2 flex">
            @foreach($follows as $follow)
                <img src="{{ asset('storage/' . ($follow->follower->profile_picture ?: 'profile_pictures/default.png')) }}"
                     alt="{{ $follow->follower->name }}'s Profile Picture"
                     class="w-8 h-8 rounded-full object-cover">
            @endforeach
        </div>
    </div>
    <div class="ml-8 py-2">
        @php
            // Collect the names and roles of users who followed the comment
            $followUsers = $follows->map(function($follow) {
                return [
                    'name' => $follow->follower->name,
                    'role' => $follow->follower->role
                ];
            })->toArray();

            $followCount = count($followUsers);
        @endphp

        @if ($followCount === 1)
            <x-role-span :role="$followUsers[0]['role']">
                <a href="{{ route('profile.show', $followUsers[0]['name']) }}" class="font-bold text-left focus:outline-none">
                    {{ $followUsers[0]['name'] }}
                </a>
            </x-role-span> vous a followé.
        @else
            @foreach($followUsers as $index => $user)
                <x-role-span :role="$user['role']">
                    {{ $user['name'] }}
                </x-role-span>
                <a href="{{ route('profile.show', $user['name']) }}" class="erah-link font-bold text-left focus:outline-none">
                    {{ $user['name'] }}
                </a>
                @if ($index === $followCount - 2)
                    et
                @elseif ($index !== $followCount - 1)
                    ,
                @endif
            @endforeach
            @if ($followCount > 3)
                , et {{ $followCount - 3 }} autres vous ont followé.
            @else
                vous ont followé.
            @endif
        @endif
    </div>
</div>
