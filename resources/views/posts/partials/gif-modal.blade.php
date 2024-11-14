<div id="searchModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden items-center justify-center">
    <div class="rounded-lg p-6 max-w-[66%] max-h-[66%] overflow-auto flex flex-col erah-box">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Recherche de GIF</h2>
            <a href="https://tenor.com/legal-terms" target="_blank" class="text-xs text-gray-400 hover:underline">Powered
                by Tenor</a>
        </div>
        <form onsubmit="performSearch(); return false;" class="flex flex-col">
            <x-text-input id="searchQuery" placeholder="Search Tenor" class="w-full mb-2"></x-text-input>
            <div id="gifResults" class="grid grid-cols-2 gap-2 mb-4 overflow-auto flex-grow"></div>
            <div class="flex justify-end">
                <x-cancel-button onclick="toggleModal()"> Annuler</x-cancel-button>
                <x-secondary-button type="submit"> Chercher</x-secondary-button> <!-- Submit button -->
            </div>
        </form>
    </div>
</div>
