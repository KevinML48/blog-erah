<x-app-layout>
    <x-slot name="header">
        {{ $post->title }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="erah-box">
                @include('posts.partials.media', ['post' => $post])

                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-lg">{{ $post->title }}</h3>

                    <!-- Like Button Section -->
                    <div class="flex items-center">
                        <!-- Likes Count -->
                        @if ($post->likes()->count() > 0)
                            <span id="likes-post-count-{{ $post->id }}">({{ $post->likes()->count() }})</span>
                        @else
                            <span id="likes-post-count-{{ $post->id }}"></span>
                        @endif

                        <!-- Unliked Button -->
                        <button onclick="likePost({{ $post->id }})" id="like-post-button-{{ $post->id }}"
                                class="flex items-center {{ $post->likes()->where('user_id', auth()->id())->exists() ? 'hidden' : '' }}">
                            <x-svg-heart id="unfilled-icon-{{ $post->id }}" :filled="false"/>
                        </button>

                        <!-- Liked Button -->
                        <button onclick="unlikePost({{ $post->id }})" id="unlike-post-button-{{ $post->id }}"
                                class="flex items-center text-red-600 {{ $post->likes()->where('user_id', auth()->id())->exists() ? '' : 'hidden' }}">
                            <x-svg-heart id="filled-icon-{{ $post->id }}" :filled="true"/>
                        </button>
                    </div>
                </div>

                @if(auth()->user() && auth()->user()->isAdmin())
                    <div class="mt-1">
                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="erah-link">
                            Éditer
                        </a>
                    </div>
                @endif

                <p class="text-gray-600">
                    @include('posts.partials.credit', ['post' => $post])
                </p>

                <div class="mt-4">
                    {!! nl2br(e($post->body)) !!}
                </div>

                @include('posts.partials.comments', ['comments' => $comments])
            </div>
        </div>
    </div>

    <script>
        function toggleLike(id, type, action) {
            const method = action === 'like' ? 'POST' : 'DELETE';
            const url = `/${type}s/${id}/${action}`;

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Network response was not ok.');
                })
                .then(data => {
                    updateLikeUI(id, type, action, data.likes_count);
                })
                .catch(error => {
                    console.error(`Error ${action === 'like' ? 'liking' : 'unliking'} ${type}:`, error);
                });
        }

        function updateLikeUI(id, type, action, likesCount) {
            const likeButton = document.querySelector(`#like-${type}-button-${id}`);
            const unlikeButton = document.querySelector(`#unlike-${type}-button-${id}`);
            const likesCountElement = document.querySelector(`#likes-${type}-count-${id}`);

            if (action === 'like') {
                likeButton.classList.add('hidden');
                unlikeButton.classList.remove('hidden');
            } else {
                likeButton.classList.remove('hidden');
                unlikeButton.classList.add('hidden');
            }

            updateLikesCount(likesCountElement, likesCount);
        }

        function likeComment(commentId) {
            toggleLike(commentId, 'comment', 'like');
        }

        function unlikeComment(commentId) {
            toggleLike(commentId, 'comment', 'unlike');
        }

        function likePost(postId) {
            toggleLike(postId, 'post', 'like');
        }

        function unlikePost(postId) {
            toggleLike(postId, 'post', 'unlike');
        }

        function updateLikesCount(likesCountElement, likesCount) {
            likesCountElement.innerText = likesCount > 0 ? `(${likesCount})` : '';
            likesCountElement.style.display = likesCount > 0 ? 'inline' : 'none';
        }
    </script>
</x-app-layout>
