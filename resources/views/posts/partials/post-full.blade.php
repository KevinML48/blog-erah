<article class="flex flex-col pb-4 mb-4">
    <!-- Media Section -->
    <figure class="media">
        @include('posts.partials.media', ['post' => $post])
    </figure>

    <section class="flex justify-between items-start">
        <header class="flex-col justify-start">
            <a href="{{ route('posts.show', $post->id) }}">
                <h4 class="font-semibold text-lg hover:underline inline-block">{{ $post->title }}</h4>
            </a>

            <!-- Theme Display -->
            <div class="mt-1">
                {{ $post->theme->name }}
            </div>

            <p class="text-gray-600">
                @include('posts.partials.credit', ['post' => $post])
            </p>
        </header>

        <!-- Like Button Section (stick to top) -->
        <section class="flex items-center self-start">
            <!-- Likes Count -->
            @if ($post->likes_count > 0)
                <span id="likes-post-count-{{ $post->id }}">({{ $post->likes_count }})</span>
            @else
                <span id="likes-post-count-{{ $post->id }}"></span>
            @endif

            @auth
                @php
                    $hasLiked = $post->likes()->where('user_id', auth()->id())->exists();
                @endphp
                    <!-- Like Button -->
                <button onclick="likePost({{ $post->id }})" id="like-post-button-{{ $post->id }}"
                        class="flex items-center {{ $hasLiked ? 'hidden' : '' }}">
                    <x-svg.heart id="unfilled-icon-{{ $post->id }}" :filled="false"/>
                </button>

                <!-- Unlike Button -->
                <div class="relative">
                    <!-- Animation Heart -->
                    <div id="unlike-post-animation-{{ $post->id }}"
                         class="absolute inset-0 flex items-center justify-center text-red-600 hidden animate-pingOnce z-0">
                        <x-svg.heart id="filled-icon-{{ $post->id }}" :filled="true"/>
                    </div>
                    <!-- Button on Top -->
                    <button onclick="unlikePost({{ $post->id }})" id="unlike-post-button-{{ $post->id }}"
                            class="flex items-center text-red-600 {{ $hasLiked ? '' : 'hidden' }} z-10">
                        <x-svg.heart id="filled-icon-{{ $post->id }}" :filled="true"/>
                    </button>
                </div>
            @else
                <a href="{{ route('posts.show.redirect.like', $post) }}" class="flex items-center text-red-600">
                    <x-svg.heart id="filled-icon-{{ $post->id }}" :filled="true"/>
                </a>
            @endauth
        </section>
    </section>

    @if(auth()->user() && auth()->user()->isAdmin())
        <footer class="mt-1">
            <a href="{{ route('admin.posts.edit', $post->id) }}" class="erah-link">
                {!! __("posts.admin.edit") !!}
            </a>
            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline-block ml-2" onsubmit="return confirmPostDelete()">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">{!! __("posts.admin.delete") !!}</button>
            </form>
        </footer>
        <script>
            function confirmPostDelete() {
                return confirm({!! __("posts.admin.confirm") !!});
            }
        </script>
    @endif

    <!-- Post Body Section -->
    <section class="mt-4">
        {!! nl2br(\App\Helpers\UrlHelper::convertUrlsToLinks($post->body)) !!}
    </section>
</article>
