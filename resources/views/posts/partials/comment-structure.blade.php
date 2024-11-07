<div class="border-l border-gray-300 py-2">
    <div class="flex items-start">
        <!-- Tree lines -->
        <div class="border-b border-gray-300 py-2 flex-shrink-0 w-12 h-10"></div>

        <!-- Comment -->
        @include('posts.partials.comment', ['comment' => $comment, 'depth' => $depth])
    </div>
</div>
