<div class="py-24">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div id="comments-container">
            @include('posts.partials.comment-loop', ['depth' => -1])
        </div>
        <div id="loader-comments-container" class="hidden flex justify-center items-center space-x-2">
            <x-spinner/>
        </div>


    </div>
</div>
