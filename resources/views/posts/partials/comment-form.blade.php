<div class="mt-4">
    <form action="{{ route('comments.store', $post->id) }}" method="POST">
        @csrf
        <textarea name="body" rows="2" class="w-full border rounded-md p-2" placeholder="Votre commentaire..."></textarea>
        <input type="hidden" name="parent_id" value="{{ $parentId ?? '' }}">
        <x-primary-button>{{ __('Poster') }}</x-primary-button>
    </form>
</div>
