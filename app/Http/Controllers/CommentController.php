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
            'body' => $request->input("input-body-$request->parent_id"),
            'media' => $mediaPath,
        ]);

        if ($request->parent_id == -1) {
            $parentId = null;
        } else {
            $parentId = $request->parent_id;
        }
        $newComment = Comment::create([
            'post_id' => $post->id,
            'parent_id' => $parentId,
            'content_id' => $commentContent->id,
        ]);

        return redirect()->route('comments.show', [$newComment->post->id, $newComment->id])->with('success', 'Commentaire ajouté.');
    }


    public function show(Post $post, Comment $comment): View
    {
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        if (!$comment) {
            redirect()->route('posts.show', [$post->id]);
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

    public function destroy(Comment $comment): RedirectResponse
    {
        $commentContent = $comment->content;

        if (
            !$comment->contentExists()
            ||
            (Auth::user()->id !== $commentContent->user_id && !Auth::user()->isAdmin())) {
            abort(403, 'Unauthorized action.');
        }


        $mediaPath = $commentContent->media;
        if ($commentContent->media && Storage::disk('public')->exists($mediaPath)) {
            Storage::disk('public')->delete($mediaPath);
        }

        $commentContent->delete();

        $firstExistingParent = $this->checkAndDeleteComment($comment);

        if ($firstExistingParent) {
            return redirect()->route('comments.show', [$comment->post->id, $firstExistingParent->id])
                ->with('success', 'Comment deleted successfully.');
        }

        return redirect()->route('posts.show', [$comment->post->id])
            ->with('success', 'Comment deleted successfully.');
    }

    protected function checkAndDeleteComment($comment)
    {
        if (!$comment->contentExists() && $comment->replies()->count() === 0) {
            $parent = $comment->parent;
            $comment->delete();

            if ($parent) {
                $parentComment = Comment::find($parent->id);
                if ($parentComment) {
                    return $this->checkAndDeleteComment($parentComment);
                }
            } else {
                return null;
            }
        }
        return $comment;
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

    public function like(Comment $comment)
    {
        // Check if the user has already liked this content
        if (!$comment->content->likes()->where('user_id', Auth::id())->exists()) {
            // Create a new like for the content associated with the comment
            $comment->content->likes()->create([
                'user_id' => Auth::id(),
            ]);
        }

        // Return a JSON response with updated likes count
        return response()->json([
            'message' => 'Comment liked successfully!',
            'likes_count' => $comment->content->likes()->count(), // Return updated like count
        ]);
    }

    public function unlike(Comment $comment)
    {
        // Find the like for the content by the current user
        $like = $comment->content->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            // Delete the like
            $like->delete();
        }

        // Return a JSON response with updated likes count
        return response()->json([
            'message' => 'Comment unliked successfully!',
            'likes_count' => $comment->content->likes()->count(), // Return updated like count
        ]);
    }
}
