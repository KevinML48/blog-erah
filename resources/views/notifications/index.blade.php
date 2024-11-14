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
                        @foreach ($notifications as $notification)
                            <div class="notification py-2">
                                @if ($notification->type === 'App\Notifications\PostPublishedNotification')
                                    @include('notifications.partials.new_post', ['post' => $notification->post])
                                @elseif($notification->type === 'App\Notifications\CommentReplyNotification')
                                    @include('notifications.partials.new_reply', ['comment' => $notification->comment])
                                @elseif($notification->type === 'App\Notifications\CommentLikeNotification')
                                    <x-notification-bundle :type="'like'" :list="$notification->likes" :count="$notification->like_count"/>
                                @elseif($notification->type === 'App\Notifications\FollowNotification')
                                    <x-notification-bundle :type="'follow'" :list="$notification->follows" :count="$notification->follow_count"/>
                                @endif
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Hidden comment form layout -->
    @include('posts.partials.comment-form')
    <!-- Hidden GIF Modal -->
    @include('posts.partials.gif-modal')

    <script src="{{ asset('js/comment-form.js') }}" defer></script>
    <script src="{{ asset('js/likes.js') }}" defer></script>
</x-app-layout>
