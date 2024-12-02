<div class="flex flex-col pb-4 mb-4">
    @include('posts.partials.media', ['post' => $post])

    <div class="flex justify-between items-center">
        <a href="{{ route('posts.show', $post->id) }}">
            <h4 class="font-semibold text-lg hover:underline inline-block">{{ $post->title }}</h4>
        </a>

        <!-- Like Button Section -->
        <div class="flex items-center">
            <!-- Likes Count -->
            @if ($post->likes->count() > 0)
                <span id="likes-post-count-{{ $post->id }}">({{ $post->likes_count }})</span>
            @else
                <span id="likes-post-count-{{ $post->id }}"></span>
            @endif

            @auth
                <!-- Unliked Button -->
                <button onclick="likePost({{ $post->id }})" id="like-post-button-{{ $post->id }}"
                        class="flex items-center {{ $post->likes->contains('user_id', auth()->id()) ? 'hidden' : '' }}">
                    <x-svg.heart id="unfilled-icon-{{ $post->id }}" :filled="false"/>
                </button>

                <!-- Liked Button -->
                <button onclick="unlikePost({{ $post->id }})" id="unlike-post-button-{{ $post->id }}"
                        class="flex items-center text-red-600 {{ $post->likes->contains('user_id', auth()->id()) ? '' : 'hidden' }}">
                    <x-svg.heart id="filled-icon-{{ $post->id }}" :filled="true"/>
                </button>
            @else
                <a href="{{ route('posts.show.redirect.like', $post) }}" class="flex items-center text-red-600">
                    <x-svg.heart id="filled-icon-{{ $post->id }}" :filled="true"/>
                </a>
            @endauth
        </div>
    </div>

    @if(auth()->user() && auth()->user()->isAdmin())
        <div class="mt-1">
            <a href="{{ route('admin.posts.edit', $post->id) }}" class="erah-link">
                {!! __("posts.admin.edit") !!}
            </a>
            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline-block ml-2" onsubmit="return confirmPostDelete()">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">{!! __("posts.admin.delete") !!}</button>
            </form>
        </div>
        <script>
            function confirmPostDelete() {
                return confirm({!! __("posts.admin.confirm") !!});
            }
        </script>
    @endif

    <!-- Theme Display -->
    <div class="mt-1">
        {{ $post->theme->name }}
    </div>

    <p class="text-gray-600">
        @include('posts.partials.credit', ['post' => $post])
    </p>


    <div class="mt-4">
        {!! nl2br(\App\Helpers\UrlHelper::convertUrlsToLinks($post->body)) !!}
    </div>
</div>

