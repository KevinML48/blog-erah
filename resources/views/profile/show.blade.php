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

            <!-- Section for Latest Comments -->
            <div class="erah-box">
                <div class="p-6">
                    <h2 class="font-bold text-lg">Derniers commentaires</h2>
                    <div class="space-y-4">
                        @foreach ($comments as $comment)
                            @include('posts.partials.comment-content', ['content' => $comment])
                        @endforeach

                        @if ($comments->isEmpty())
                            <p>Aucun commentaire trouvé.</p>
                        @endif
                    </div>

                    <!-- Link to View All Comments -->
                    <div class="mt-4">
                        <a href="{{ route('profile.comments', ['username' => $user->name]) }}"
                           class="text-blue-600 hover:underline">
                            Voir tous les commentaires
                        </a>
                    </div>

                    <!-- Link to View All Comment Likes -->
                    <div class="mt-4">
                        <a href="{{ route('profile.likes.comments', ['username' => $user->name]) }}"
                           class="text-blue-600 hover:underline">
                            Voir tous les commentaires likés
                        </a>
                    </div>

                    <!-- Link to View All Post Likes -->
                    <div class="mt-4">
                        <a href="{{ route('profile.likes.posts', ['username' => $user->name]) }}"
                           class="text-blue-600 hover:underline">
                            Voir tous les posts likés
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('js/follow.js') }}" defer></script>
</x-app-layout>
