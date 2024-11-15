<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Notifications') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($notifications->isEmpty())
                        <p>{{ __('Aucune notification pour le moment.') }}</p>
                    @else
                        <div id="notifications-container" data-next-page-url="{{ $notifications->nextPageUrl() }}">
                            @include('notifications.partials.notification-loop', ['notifications' => $notifications])
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="loader" style="display: none;">Loading...</div>

    <!-- Hidden comment form layout -->
    @include('posts.partials.comment-form')
    <!-- Hidden GIF Modal -->
    @include('posts.partials.gif-modal')

    <script src="{{ asset('js/comment-form.js') }}" defer></script>
    <script src="{{ asset('js/likes.js') }}" defer></script>
    <script src="{{ asset('js/load-notifications.js') }}" defer></script>

    <script>

    </script>

</x-app-layout>
