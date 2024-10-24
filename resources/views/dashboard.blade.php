<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="erah-box">
                <div class="p-6 text-white">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
