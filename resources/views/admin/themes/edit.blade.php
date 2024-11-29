<x-app-layout>
    <x-slot name="title">
        {{ __('Éditer Thème') }}
    </x-slot>

    <x-slot name="header">
        {{ __('Éditer Thème') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="erah-box">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.themes.update', $theme) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <x-text-input
                                id="name"
                                name="name"
                                type="text"
                                :value="old('name', $theme->name)"
                                required
                            />

                            <x-text-input
                                id="slug"
                                name="slug"
                                type="text"
                                :value="old('slug', $theme->slug)"
                                required
                            />

                            <x-button.primary>
                                {{ __('Update') }}
                            </x-button.primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
