<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('Image de profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __("Mettez à jour votre image de profil.") }}
        </p>
    </header>

    @if(Auth::user()->profile_picture)
        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture"
             class="w-48 h-48 rounded-full object-fill">
    @endif

    <form action="{{ route('profile.update.picture') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <input type="file" name="profile_picture" id="profile_picture" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-white"
                   accept="image/*">
            @error('profile_picture')
            <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>

            @if (session('status') === 'profile-picture-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Image de profil changée.') }}</p>
            @endif
        </div>
    </form>
</section>
