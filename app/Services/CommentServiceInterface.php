<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CommentServiceInterface
{
    public function store($userId, $postId, $parentId, $body, $mediaPath, $gifUrl): Comment;

    public function show(Comment $comment): LengthAwarePaginator;

    public function loadMoreComments(Post $post, $currentPage, array $existingCommentIds): LengthAwarePaginator;

    public function loadMoreReplies(Comment $comment, $currentPage, array $existingReplyIds): LengthAwarePaginator;

    public function destroy(Comment $comment): ?Comment;

    public function like(Comment $comment): int;

    public function unlike(Comment $comment): int;

    public function searchTenor($query): ?array;
}
