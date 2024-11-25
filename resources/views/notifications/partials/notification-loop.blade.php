@foreach ($notifications as $notification)
    <div class="notification py-4 relative {{ $notification->unread ? '' : '' }}">
        @if($notification->unread)
            <span class="absolute -left-5 top-5 transform -translate-y-1/3 w-2.5 h-2.5 bg-blue-500 rounded-full animate-ping"></span>
        @endif
            @if(isset($notification->view) && view()->exists($notification->view))
                {{-- Render the Blade view with arguments --}}
                @include($notification->view, array_merge($notification->args ?? [], ['notification' => $notification]))
            @elseif(isset($notification->view))
                {{-- Render a Blade component --}}
                @component($notification->view, array_merge($notification->args ?? [], ['notification' => $notification]))
                @endcomponent
            @endif
    </div>
@endforeach
