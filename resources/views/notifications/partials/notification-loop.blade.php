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
