@foreach ($notifications as $notification)
    <div class="notification py-4 relative {{ $notification->unread ? '' : '' }}">
        @if($notification->unread)
            <span class="absolute -left-5 top-5 transform -translate-y-1/3 w-2.5 h-2.5 bg-blue-500 rounded-full animate-ping"></span>
        @endif
        {!! $notification->body !!}
    </div>
@endforeach
