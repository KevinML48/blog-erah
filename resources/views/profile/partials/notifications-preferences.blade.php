<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('Préférences de notifications') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __("Mettez à jour vos préférences de notifications.") }}
        </p>
    </header>
    @include('notifications.partials.preferences')
</section>
