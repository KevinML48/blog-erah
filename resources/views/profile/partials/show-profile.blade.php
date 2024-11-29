<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('profile.your_profile') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __('profile.displays_your_profile') }}
        </p>
    </header>
    <a href="{{ route('profile.show', Auth::user()->username) }}" class="erah-link-amnesic">
        {!!  __('profile.link') !!}
    </a>
</section>
