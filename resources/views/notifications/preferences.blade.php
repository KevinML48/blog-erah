<x-app-layout>
    <x-slot name="header">
            {{ __('Préférences') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="erah-box">
                <div class="">

                    @if(session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                        <form action="{{ route('user.notification.preferences.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <h3>{{ __('Me notifier lorsqu\'un nouveau post est publié dans :') }}</h3>

                            <div class="preferences py-2">
                                @foreach($themes as $theme)
                                    <div class="preference-option">
                                        <input type="checkbox"
                                               name="preferences[{{ $theme->id }}]"
                                               id="theme-{{ $theme->id }}"
                                            {{ $preferences->get($theme->id)?->is_enabled ? 'checked' : '' }}>
                                        <label for="theme-{{ $theme->id }}">
                                            {{ $theme->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <button class="erah-button" type="submit">{{ __('Enregistrer les préférences') }}</button>
                        </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
