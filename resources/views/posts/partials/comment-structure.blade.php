<div class="border-l border-gray-300 py-2">
    <div class="flex items-start">
        <!-- Tree lines -->
        <div class="border-b border-gray-300 py-2 flex-shrink-0 w-5 h-10 md:w-12"></div>

        <!-- Comment -->
        @include('posts.partials.comment', ['comment' => $comment, 'depth' => $depth])
    </div>
</div>
