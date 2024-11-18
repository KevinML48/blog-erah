@foreach ($notifications as $notification)
    <div class="notification py-2">
        {!! $notification->body !!}
    </div>
@endforeach
