<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>S'est inscrit le :</strong> {{ $user->created_at->format('d M, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
