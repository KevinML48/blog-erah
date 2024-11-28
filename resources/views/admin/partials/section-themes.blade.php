<!-- Themes list -->
<div class="erah-box">
    <h2 class="mt-6 text-lg font-semibold">Thèmes</h2>
    <div class="mt-4">
        <a href="{{ route('admin.themes.create') }}" class="py-2 px-4 erah-link">
            Créer Nouveau Thème
        </a>
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-semibold">Tous les Thèmes</h3>
        <div class="space-y-4 mt-4">
            <table class="min-w-full mt-4 border border-gray-200">
                <thead>
                <tr class="text-left">
                    <th class="px-6 py-3 text-sm font-medium text-gray-500">Nom</th>
                    <th class="px-6 py-3 text-sm font-medium text-gray-500">Slug</th>
                    <th class="px-6 py-3 text-sm font-medium text-gray-500">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($themes as $theme)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $theme->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $theme->slug }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.themes.edit', $theme) }}" class="text-blue-600 hover:text-blue-900">Éditer</a>

                            <form action="{{ route('admin.themes.destroy', $theme) }}" method="POST" class="inline-block ml-2" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Tous les posts seront supprimés. Continuer?");
    }
</script>
