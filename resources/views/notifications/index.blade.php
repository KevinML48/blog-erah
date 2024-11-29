<x-app-layout>
    <x-slot name="title">
        {{ __('notifications.title') }}
    </x-slot>
    <x-slot name="header">
        {{ __('notifications.title') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->unreadNotificationsCount() > 0)
                <div class="erah-box">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <p class="mt-1 text-sm text-gray-400">
                                    {{ __("notifications.reead.title") }}
                                </p>
                            </header>
                            <a href="{{ route('notifications.read', Auth::user()->name) }}" class="erah-link-amnesic">
                                {!! __('notifications.read.mark') !!}
                            </a>
                        </section>
                    </div>
                </div>
            @endif
            <div class="shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($notifications->isEmpty())
                        <p>{{ __('notifications.empty') }}</p>
                    @else
                        <div id="notifications-container" data-next-page-url="{{ $notifications->nextPageUrl() }}">
                            @include('notifications.partials.notification-loop', ['notifications' => $notifications])
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="loader" style="display: none;">
        <x-spinner/>
    </div>

    <!-- Hidden comment form layout -->
    @include('posts.partials.comment-form')
    <!-- Hidden GIF Modal -->
    @include('posts.partials.gif-modal')

    <script src="{{ asset('js/comment-form.js') }}" defer></script>
    <script src="{{ asset('js/likes.js') }}" defer></script>
    <script src="{{ asset('js/load-notifications.js') }}" defer></script>
    <script src="{{ asset('js/follow.js') }}" defer></script>

    <script>

    </script>

</x-app-layout>
