<x-app-layout>
    <x-slot name="title">
        {{ __('Notifications') }}
    </x-slot>
    <x-slot name="header">
        <h2>{{ __('Notifications') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->unreadNotificationsCount() > 0)
                <div class="erah-box">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <p class="mt-1 text-sm text-gray-400">
                                    {{ __("Marquer toutes vos notifications comme lues.") }}
                                </p>
                            </header>
                            <a href="{{ route('notifications.read', Auth::user()->name) }}" class="erah-link-amnesic">
                                Marquer comme lues →
                            </a>
                        </section>
                    </div>
                </div>
            @endif
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
