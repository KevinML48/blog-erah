<div class="py-2 flex-grow">

    @if ($comment->contentExists())
    <!-- Content section -->
        @include('posts.partials.comment-content', ['content' => $comment->content])
    <!-- React section -->
        @include('posts.partials.comment-reaction', ['comment' => $comment])
    @else
        <div class="py-5 p-2">
            <span class="text-red-500 italic">Commentaire introuvable.</span>
        </div>
    @endif

    <!-- Display Replies -->
    <div id="replies-container-{{ $comment->id }}" class="ml-6 mt-2">
        @if (isset($depth) && ($depth < 1 && $depth >= 0))
            @foreach ($comment->replies()->take(2)->get() as $reply)
                @include('posts.partials.comment-structure', ['comment' => $reply, 'depth' => $depth + 1])
            @endforeach
        @endif
    </div>

    @if (isset($depth) && $depth >= 1)
        @if ($comment->replies()->count() > 0)
                <div class="load-more-container">
                    <button class="load-more-replies text-sm text-blue-600"
                            data-url="{{ route('comments.loadMoreReplies', ['comment' => $comment->id]) }}"
                            data-page="0"
                            onclick="loadMore(this, '{{ route('comments.loadMoreReplies', ['comment' => $comment->id]) }}')">
                        Charger plus de commentaires
                    </button>
                    <div class="loader hidden ml-2">
                        <x-spinner/>
                    </div>
                </div>
        @endif
    @else
        @if ($comment->replies()->count() > 1 && isset($depth) && $depth >= 0)
                <div class="load-more-container">
                    <button class="load-more-replies text-sm text-blue-600"
                            data-url="{{ route('comments.loadMoreReplies', ['comment' => $comment->id]) }}"
                            data-page="0"
                            onclick="loadMore(this, '{{ route('comments.loadMoreReplies', ['comment' => $comment->id]) }}')">
                        Charger plus de commentaires
                    </button>
                    <div class="loader hidden ml-2">
                        <x-spinner/>
                    </div>
                </div>
        @endif
    @endif
</div>
