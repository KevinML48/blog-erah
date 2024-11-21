<x-app-layout>
    <x-slot name="title">
        {{ $user->name }}
    </x-slot>
    <x-slot name="header">
        <x-role-span :role="$user->role">{{ $user->name }}</x-role-span>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="erah-box flex flex-col md:flex-row">
                <div class="md:w-1/3 ml-6">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
                             class="w-48 h-48 rounded-full object-fill ml-6">
                    @endif
                    <div class="py-2 ml-6">
                        @if(auth()->user()->isAdmin())
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                        @endif
                        <p><strong>S'est inscrit le :</strong> {{ $user->created_at->format('d M, Y') }}</p>
                    </div>
                    @if(auth()->user() != $user)
                        <div class="ml-6">
                            <button id="unfollow-button-{{ $user->id }}"
                                    class="follow-button inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 transition ease-in-out duration-150 {{ auth()->user()->isFollowing($user) ? '' : 'hidden' }}"
                                    data-following="true"
                                    onclick="unfollowUser({{ $user->id }})"
                                    data-user-id="{{ $user->id }}">
                                Se désabonner
                            </button>
                            <button id="follow-button-{{ $user->id }}"
                                    class="follow-button inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 transition ease-in-out duration-150 {{ auth()->user()->isFollowing($user) ? 'hidden' : '' }}"
                                    data-following="false"
                                    onclick="followUser({{ $user->id }})"
                                    data-user-id="{{ $user->id }}">
                                S'abonner
                            </button>
                        </div>
                    @endif
                </div>

                @if($user->description)
                    <div class="ml-6 mt-4 md:w-2/3">
                        {!! nl2br(e($user->description)) !!}
                    </div>
                @endif
            </div>


            <div class="erah-boxh">
                <x-navigator
                    :default="'comments'"
                    :scroll="true"
                    :triggers="[
                        'comments' => view('components.navigator-trigger', ['trigger' => 'comments'])->with('label', 'Commentaires'),
                        'likes' => view('components.navigator-trigger', ['trigger' => 'account'])->with('label', 'J\'aimes'),
                        'post-likes' => view('components.navigator-trigger', ['trigger' => 'post-likes'])->with('label', 'Posts aimés'),
                    ]"
                    :sections="[
                        'comments' => view('profile.partials.section-comments', ['contents' => $contents]),
                        'likes' => view('profile.partials.section-likes', ['contents' => $likes]),
                        'post-likes' => view('profile.partials.section-post-likes', ['posts' => $postLikes]),
                    ]"
                    :functions="[
                        'comments' => 'profileLoadMoreComments',
                        'likes' => 'profileLoadMoreLikedComments',
                        'post-likes' => 'profileLoadMoreLikedPosts',
                    ]"
                    :functionsAttribute="$user->name"
                />
            </div>
        </div>
    </div>

    <script src="{{ asset('js/comment-form.js') }}" defer></script>
    <script src="{{ asset('js/follow.js') }}" defer></script>
    <script src="{{ asset('js/likes.js') }}" defer></script>
    <script src="{{ asset('js/load-more-profile.js') }}" defer></script>
    <!-- Hidden comment form layout -->
    @include('posts.partials.comment-form')
    <!-- Hidden GIF Modal -->
    @include('posts.partials.gif-modal')
</x-app-layout>
