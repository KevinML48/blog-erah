<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome message -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{ __("You're an admin!") }}
                </div>
            </div>

            <!-- Users list -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h2 class="mt-6 text-lg font-semibold">Last 10 Users</h2>
                    <table class="min-w-full mt-4 bg-white border border-gray-200">
                        <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">{{ __('Name') }}</th>
                            <th class="py-2 px-4 border-b">{{ __('Email') }}</th>
                            <th class="py-2 px-4 border-b">{{ __('Role') }}</th>
                            <th class="py-2 px-4 border-b">{{ __('Created At') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="py-2 px-4 border-b">
                                    <a href="{{ route('profile.show', ['username' => $user->name]) }}" class="text-blue-600 hover:underline">
                                        {{ $user->name }}
                                    </a>
                                </td>
                                <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                                <td class="py-2 px-4 border-b">{{ $user->role }}</td>
                                <td class="py-2 px-4 border-b">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Posts -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h2 class="mt-6 text-lg font-semibold">Posts</h2>
                <div class="mt-4">
                    <a href="{{ route('admin.posts.create') }}" class="text-blue-600 px-4 hover:underline">
                        Créer Nouveau Post
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
