<x-app-layout>
    <x-slot name="header">
        Votre Fil
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="erah-box">
                <div id="comments-container">
                    @foreach($contents as $content)
                        @include('posts.partials.comment', ['comment' => $content->comment])
                    @endforeach
                </div>

                <div id="loading" style="display: none;">Loading...</div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/comment-form.js') }}" defer></script>
    <script src="{{ asset('js/follow.js') }}" defer></script>
    <script src="{{ asset('js/likes.js') }}" defer></script>

    <script>
        let nextPageUrl = "{{ $contents->nextPageUrl() }}";
        let loading = false;

        function loadMoreComments() {
            if (!nextPageUrl || loading) return;
            loading = true;
            document.getElementById('loading').style.display = 'block';

            fetch(nextPageUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('comments-container').insertAdjacentHTML('beforeend', data.html);
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
