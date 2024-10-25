<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('Profile Picture') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __("Update your account's profile picture.") }}
        </p>
    </header>

    @if(Auth::user()->profile_picture)
        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture"
             class="w-24 h-24 rounded-full object-fill">
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

        <x-primary-button>{{ __('Upload') }}</x-primary-button>
    </form>
</section>
