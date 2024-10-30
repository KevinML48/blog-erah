<div class="mt-4">
    <form action="{{ route('comments.store', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @error('body')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
        <textarea name="body" rows="2" class="w-full border rounded-md p-2"
                  placeholder="Votre commentaire..." maxlength="255" id="commentBody"></textarea>
        <div class="flex justify-between items-center">
            <div id="mediaUpload" class="media-upload">
                <input type="file" name="media" id="media"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-white" accept="image/*">
                @error('media')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex">
{{--                <div class="counter mr-2">--}}
{{--                    <span id="current">0</span>--}}
{{--                    <span id="maximum">/ 255</span>--}}
{{--                </div>--}}
                <x-primary-button>{{ __('Poster') }}</x-primary-button>
            </div>
        </div>
        <input type="hidden" name="parent_id" value="{{ $parentId ?? '' }}">
    </form>
</div>

{{--<script>--}}
{{--    const commentBody = document.getElementById('commentBody');--}}
{{--    const currentCount = document.getElementById('current');--}}

{{--    commentBody.addEventListener('input', function () {--}}
{{--        currentCount.textContent = commentBody.value.length;--}}
{{--    });--}}
{{--</script>--}}
