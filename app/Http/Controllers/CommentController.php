<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\CommentContent;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $mediaPath = null;

        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('comment_media', 'public');
        } elseif ($request->filled('gif_url')) {
            $mediaPath = $request->gif_url;
        }

        $commentContent = CommentContent::create([
            'user_id' => auth()->id(),
            'body' => $request->body,
            'media' => $mediaPath,
        ]);
        if ($request->parent_id === -1) {
            $parentId = null;
        } else {
            $parentId = $request->parent_id;
        }
        Comment::create([
            'post_id' => $post->id,
            'parent_id' => $parentId,
            'content_id' => $commentContent->id,
        ]);

        return redirect()->route('posts.show', $post->id)->with('success', 'Commentaire ajouté.');
    }


    public function show(Post $post, Comment $comment): View
    {
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        $comments = Comment::with(['content.user', 'replies.content.user'])
            ->where('id', $comment->id)
            ->paginate(5);

        return view('posts.show', compact('post', 'comments', 'comment'));
    }

    public function loadMoreComments(Post $post, Request $request): JsonResponse
    {
        $currentPage = $request->input('page', 1);
        $comments = Comment::with(['content.user', 'replies.content.user'])
            ->where('post_id', $post->id)
            ->whereNull('parent_id')
            ->paginate(5, ['*'], 'page', $currentPage + 1);

        return response()->json([
            'comments' => view('posts.partials.comments-loop', compact('comments'))->render(),
            'hasMore' => $comments->hasMorePages(),
        ]);
    }

    public function loadMoreReplies(Comment $comment, Request $request): JsonResponse
    {
        $currentPage = $request->input('page', 1);
        $replies = $comment->replies()
            ->paginate(2, ['*'], 'page', $currentPage + 1);

        return response()->json([
            'commentId' => $comment->id,
            'replies' => view('posts.partials.replies-loop', compact('replies'))->render(),
            'hasMore' => $replies->hasMorePages(),
        ]);
    }

    public function destroy($id): RedirectResponse
    {
        $commentContent = CommentContent::findOrFail($id);

        if (Auth::user()->id !== $commentContent->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $comment = $commentContent->comment;

        $mediaPath = $commentContent->media;
        if ($commentContent->media && Storage::disk('public')->exists($mediaPath)) {
            Storage::disk('public')->delete($mediaPath);
        }

        $commentContent->delete();

        $this->checkAndDeleteComment($comment);

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

    protected function checkAndDeleteComment($comment): void
    {

        if (!$comment->contentExists() && $comment->replies()->count() === 0) {
            $parent = $comment->parent;
            $comment->delete();

            if ($parent) {
                $parentComment = Comment::find($parent->id);
                if ($parentComment) {
                    $this->checkAndDeleteComment($parentComment);
                }
            }
        }
    }

    public function searchTenor(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json(['error' => 'No search query provided'], 400);
        }

        $apiKey = env('TENOR_API_KEY');
        $clientKey = 'discord-bot';
        $limit = 100;

        $response = Http::withOptions(['verify' => false])->get("https://tenor.googleapis.com/v2/search", [
            'q' => $query,
            'key' => $apiKey,
            'client_key' => $clientKey,
            'limit' => $limit,
            'media_filter' => 'gif',
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Unable to fetch GIFs'], 500);
        }
    }

}
