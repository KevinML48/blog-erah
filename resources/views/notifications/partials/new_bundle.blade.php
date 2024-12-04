<div class="new-{{ $type }}-notification py-2">
    <div class="py-2 flex items-center">
        {{-- Icon --}}
        <div class="text-red-600">
            @if ($type === 'like')
                <x-svg.heart :filled="true"/>
            @elseif ($type === 'follow')
                <x-svg.follow/>
            @endif
        </div>
        {{-- Profile Pictures --}}
        <div class="ml-2 flex">
            @foreach($users as $user)
                <x-user.profile-picture :user="$user" :default="false"/>
            @endforeach
        </div>
    </div>
    {{-- Usernames --}}
    <div class="ml-8 py-2">
        {{-- If more than 3 likes or follows --}}
        @if ($count > 3)
            {{-- Take the 3 first users --}}
            @foreach($users->take(3) as $user)
                <x-user.name :user="$user"/>
                {{-- If the current index is not 2 (i.e., not the third and last user), use a comma --}}
                @if ($index !== 2)
                    ,
                @endif
            @endforeach
            {{-- Count the remaining elements --}}
            {!! __('notifications.bundle.and-others', ['count' => $count - 3]) !!}
        @else
            {{-- If 3 or fewer likes or follows --}}
            @foreach($users as $index => $user)
                <x-user.name :user="$user"/>
                {{-- If the index is the second-to-last user, insert "et" --}}
                @if ($index === $count - 2)
                    {!! __('notifications.bundle.and') !!}
                    {{-- If index is not the last, use a comma --}}
                @elseif ($index !== $count - 1)
                    ,
                @endif
            @endforeach
        @endif

        {{-- Correct pluralization based on type and count --}}
        @if ($count === 1)
            @if ($type === 'like')
                {!! __('notifications.bundle.like.single') !!}
            @elseif ($type === 'follow')
                {!! __('notifications.bundle.follow.single') !!}
            @endif
        @elseif ($count > 1)
            @if ($type === 'like')
                {!! __('notifications.bundle.like.plural') !!}
            @elseif ($type === 'follow')
                {!! __('notifications.bundle.follow.plural') !!}
            @endif
        @endif

        @if($type === 'like')
            @include('posts.partials.comment-content', ['content' => $likeable, 'showMedia' => false])
        @endif
    </div>
</div>
