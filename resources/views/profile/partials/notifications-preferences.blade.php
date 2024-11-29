<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('profile.form.notifications.title') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __('profile.form.notifications.details') }}
        </p>
    </header>
    @include('notifications.partials.preferences')
</section>
