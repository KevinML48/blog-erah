<x-app-layout>
    <x-slot name="title">
        {{ __('Créer Thème') }}
    </x-slot>

    <x-slot name="header">
        {{ __('Créer Thème') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="erah-box">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.themes.create') }}">
                        @csrf
                        <div class="space-y-6">
                            <x-text-input
                                id="name"
                                name="name"
                                type="text"
                                placeholder="Nom"
                                :value="old('name')"
                                required
                            />

                            <x-text-input
                                id="slug"
                                name="slug"
                                type="text"
                                placeholder="Slug"
                                :value="old('slug')"
                                required
                            />

                            <x-primary-button>
                                {{ __('Create') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
