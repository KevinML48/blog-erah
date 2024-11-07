<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentServiceInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected CommentServiceInterface $commentService;

    public function __construct(CommentServiceInterface $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $userId = auth()->id();
        $postId = $post->id;
        $parentId = $request->parent_id == -1 ? null : $request->parent_id;
        $body = $request->input("input-body-$parentId");
        $mediaPath = null;

        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('comment_media', 'public');
        } elseif ($request->filled('gif_url')) {
            $mediaPath = $request->gif_url;
        }

        $newComment = $this->commentService->store($userId, $postId, $parentId, $body, $mediaPath, $request->gif_url);

        return redirect()->route('comments.show', [$newComment->post->id, $newComment->id])
            ->with('success', 'Commentaire ajouté.');
    }

    public function show(Post $post, Comment $comment): View
    {
        $comments = $this->commentService->show($post, $comment);
        return view('posts.show', compact('post', 'comments', 'comment'));
    }

    public function loadMoreComments(Post $post, Request $request): JsonResponse
    {
        $currentPage = $request->input('page', 1);
        $comments = $this->commentService->loadMoreComments($post, $currentPage);

        return response()->json([
            'comments' => view('posts.partials.comments-loop', compact('comments'))->render(),
            'hasMore' => $comments->hasMorePages(),
        ]);
    }

    public function loadMoreReplies(Comment $comment, Request $request): JsonResponse
    {
        $currentPage = $request->input('page', 1);
        $comments = $this->commentService->loadMoreReplies($comment, $currentPage);

        return response()->json([
            'commentId' => $comment->id,
            'replies' => view('posts.partials.comment-structure-loop', compact('comments'))->render(),
            'hasMore' => $comments->hasMorePages(),
        ]);
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $firstExistingParent = $this->commentService->destroy($comment);

        if ($firstExistingParent) {
            return redirect()->route('comments.show', [$comment->post->id, $firstExistingParent->id])
                ->with('success', 'Commentaire supprimé.');
        }

        return redirect()->route('posts.show', [$comment->post->id])
            ->with('success', 'Commentaire supprimé.');
    }

    public function like(Comment $comment): JsonResponse
    {
        $likesCount = $this->commentService->like($comment);

        return response()->json([
            'message' => 'Comment liked successfully!',
            'likes_count' => $likesCount,
        ]);
    }

    public function unlike(Comment $comment): JsonResponse
    {
        $likesCount = $this->commentService->unlike($comment);

        return response()->json([
            'message' => 'Comment unliked successfully!',
            'likes_count' => $likesCount,
        ]);
    }

    public function searchTenor(Request $request): JsonResponse
    {
        $query = $request->input('query');
        $tenorResults = $this->commentService->searchTenor($query);

        if ($tenorResults === null) {
            return response()->json(['error' => 'No search query provided'], 400);
        }

        return response()->json($tenorResults);
    }
}
