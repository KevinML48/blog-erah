@props(['user'])

<div class="flex flex-col w-64 rounded-lg shadow-md overflow-hidden transition-transform transform">
    <!-- Profile Picture and Name Section -->
    <div class="flex flex-col items-start py-4 ml-2">
        <!-- Profile Picture -->
        <div class="flex justify-center">
            <x-user-profile-picture :user="$user" :size="20" :border="true" :card="false"/>
        </div>

        <!-- Name -->
        <div class="text-left ml-2">
            <x-username :user="$user" />
        </div>
    </div>

    <!-- Follow/Unfollow Button Section (Positioned Top Right) -->
    @if(Auth::user()->id != $user->id)
        <div class="absolute top-0 right-0 mt-2 mr-2">
            <button id="unfollow-button-{{ $user->id }}"
                    class="unfollow-button-{{ $user->id }} follow-button inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 transition ease-in-out duration-150 {{ auth()->user()->isFollowing($user) ? '' : 'hidden' }}"
                    data-following="true"
                    onclick="unfollowUser({{ $user->id }})"
                    data-user-id="{{ $user->id }}">
                Se désabonner
            </button>
            <button id="follow-button-{{ $user->id }}"
                    class="follow-button-{{ $user->id }} follow-button inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 transition ease-in-out duration-150 {{ auth()->user()->isFollowing($user) ? 'hidden' : '' }}"
                    data-following="false"
                    onclick="followUser({{ $user->id }})"
                    data-user-id="{{ $user->id }}">
                S'abonner
            </button>
        </div>
    @endif

    <!-- Description -->
    <div class="mt-2 text-left text-sm text-gray-600 px-4">
        {{ $user->description }}
    </div>
</div>
