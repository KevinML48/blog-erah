<x-app-layout>
    <x-slot name="title">
        {{ __('profile.thread') }}
    </x-slot>
    <x-slot name="header">
        {{ __('profile.thread') }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="erah-box">
                <div id="comments-container">
                    @foreach($contents as $content)
                        @include('posts.partials.comment', ['comment' => $content->comment])
                    @endforeach
                </div>

                <div id="loading" class="hidden justify-center items-center space-x-2">
                    <x-spinner/>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/comment-form.js') }}" defer></script>
    <script src="{{ asset('js/follow.js') }}" defer></script>
    <script src="{{ asset('js/likes.js') }}" defer></script>
    <!-- Hidden comment form layout -->
    @include('posts.partials.comment-form')
    <!-- Hidden GIF Modal -->
    @include('posts.partials.gif-modal')

    <script>
        let nextPageUrl = "{{ $contents->nextPageUrl() }}";
        let loading = false;

        function loadMoreComments() {
            if (!nextPageUrl || loading) return;
            loading = true;
            document.getElementById('loading').style.display = 'flex';

            fetch(nextPageUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('comments-container').insertAdjacentHTML('beforeend', data.html);
                    convertTimes();
                    nextPageUrl = data.next_page_url;
                    loading = false;
                })
                .catch(error => {
                    console.error('Error loading more comments:', error);
                    document.getElementById('loading').style.display = 'none';
                    loading = false;
                });
        }

        window.addEventListener('scroll', () => {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
                loadMoreComments();
            }
        });
    </script>

</x-app-layout>
