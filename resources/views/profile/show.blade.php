<x-app-layout>
    <x-slot name="header">
        {{ $user->name }}
    </x-slot>
    <x-slot name="header">
        {{ $user->name }}
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="erah-box">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
                         class="w-24 h-24 rounded-full object-fill">
                @endif
                <div class="p-6">
                    @if(auth()->user()->isAdmin())
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                    @endif
                    <p><strong>S'est inscrit le :</strong> {{ $user->created_at->format('d M, Y') }}</p>
                </div>
                @if(auth()->user() != $user)
                    <div>
                        <button id="unfollow-button-{{ $user->id }}"
                                class="follow-button {{ auth()->user()->isFollowing($user) ? '' : 'hidden' }}"
                                data-following="true"
                                onclick="unfollowUser({{ $user->id }})"
                                data-user-id="{{ $user->id }}">
                            Se désabonner
                        </button>
                        <button id="follow-button-{{ $user->id }}"
                                class="follow-button {{ auth()->user()->isFollowing($user) ? 'hidden' : '' }}"
                                data-following="false"
                                onclick="followUser({{ $user->id }})"
                                data-user-id="{{ $user->id }}">
                            S'abonner
                        </button>
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
                />
            </div>
        </div>
    </div>

    <script src="{{ asset('js/comment-form.js') }}" defer></script>
    <script src="{{ asset('js/follow.js') }}" defer></script>
    <script src="{{ asset('js/likes.js') }}" defer></script>
</x-app-layout>
