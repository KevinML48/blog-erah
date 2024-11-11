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
                        <ul>
                            @foreach($notifications as $notification)
                                <li class="mb-4 notification-item">
                                    <div class="notification-content">
                                        <!-- Display the notification message or a fallback text -->
                                        {{ $notification->data['message'] ?? __('Notification sans contenu.') }}
                                    </div>
                                    <div class="notification-time text-sm">
                                        {{ $notification->created_at->format('d M Y H:i') }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
