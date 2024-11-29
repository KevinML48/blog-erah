<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('profile.user.picture') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __("profile.form.picture.details") }}
        </p>
    </header>

    <div class="flex flex-row items-end">
        <x-user.profile-picture :user="Auth::user()" :default="false" :size="48" :border="false"/>
        <x-user.profile-picture :user="Auth::user()" :default="false"/>
    </div>

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
            <x-button.primary>{{ __('profile.save') }}</x-button.primary>

            @if (session('status') === 'profile-picture-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('profile.user.picture_updated') }}</p>
            @endif
        </div>
    </form>
</section>
