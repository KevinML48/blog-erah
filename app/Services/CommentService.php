<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\CommentContent;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CommentService implements CommentServiceInterface
{
    public function store($userId, $postId, $parentId, $body, $mediaPath): Comment
    {
        $parentId = $parentId == -1 ? null : $parentId;
        $comment = Comment::create([
            'post_id' => (int)$postId,
            'parent_id' => $parentId,
        ]);

        CommentContent::create([
            'user_id' => $userId,
            'body' => $body,
            'media' => $mediaPath,
            'comment_id' => $comment->id,
        ]);

        return $comment;
    }

    public function show(Comment $comment): LengthAwarePaginator
    {
        return Comment::with(['content.user', 'replies.content.user'])
            ->where('id', $comment->id)
            ->paginate(5);
    }

    public function loadMoreComments(Post $post, $currentPage, array $existingCommentIds): LengthAwarePaginator
    {
        $comments = Comment::with(['content.user', 'replies.content.user'])
            ->where('post_id', $post->id)
            ->whereNull('parent_id')
            ->paginate(5, ['*'], 'page', $currentPage + 1);

        $filteredComments = $comments->getCollection()->reject(function ($comment) use ($existingCommentIds) {
            return in_array($comment->id, $existingCommentIds);
        });

        $comments->setCollection($filteredComments);

        return $comments;
    }


    public function loadMoreReplies(Comment $comment, $currentPage, array $existingReplyIds): LengthAwarePaginator
    {
        $comments = $comment->replies()->paginate(2, ['*'], 'page', $currentPage + 1);

        $filteredReplies = $comments->getCollection()->reject(function ($reply) use ($existingReplyIds) {
            return in_array($reply->id, $existingReplyIds);
        });

        $comments->setCollection($filteredReplies);

        return $comments;
    }

    public function destroy(Comment $comment): ?Comment
    {
        $commentContent = $comment->content;

        if (!$comment->contentExists() || (Auth::user()->id !== $commentContent->user_id && !Auth::user()->isAdmin())) {
            abort(403);
        }

        $mediaPath = $commentContent->media;
        if ($commentContent->media && Storage::disk('public')->exists($mediaPath)) {
            Storage::disk('public')->delete($mediaPath);
        }

        $commentContent->delete();

        return $this->checkAndDeleteComment($comment);
    }

    protected function checkAndDeleteComment($comment): ?Comment
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

    public function like(Comment $comment): int
    {
        if (!$comment->content->likes()->where('user_id', Auth::id())->exists()) {
            $comment->content->likes()->create([
                'user_id' => Auth::id(),
            ]);
        }

        return $comment->content->likes()->count();
    }

    public function unlike(Comment $comment): int
    {
        $like = $comment->content->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
        }

        return $comment->content->likes()->count();
    }

    public function searchTenor($query): ?array
    {
        if (empty($query)) {
            return null;
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

        return $response->successful() ? $response->json() : null;
    }
}
