<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div id="likes-container">
            @include('posts.partials.content-loop')
        </div>
        <div id="loader" class="hidden flex justify-center items-center space-x-2">
            <x-spinner/>
        </div>

    </div>
</div>
