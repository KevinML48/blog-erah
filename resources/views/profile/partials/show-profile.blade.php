<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('Votre profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __("Affiche votre profil.") }}
        </p>
    </header>
    <a href="{{ route('profile.show', Auth::user()->username) }}" class="erah-link-amnesic">
        Profil →
    </a>
</section>
