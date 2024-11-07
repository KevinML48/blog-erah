<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CommentServiceInterface
{
    public function store($userId, $postId, $parentId, $body, $mediaPath, $gifUrl): Comment;

    public function show(Post $post, Comment $comment): LengthAwarePaginator;

    public function loadMoreComments(Post $post, $currentPage): LengthAwarePaginator;

    public function loadMoreReplies(Comment $comment, $currentPage): LengthAwarePaginator;

    public function destroy(Comment $comment): ?Comment;

    public function like(Comment $comment): int;

    public function unlike(Comment $comment): int;

    public function searchTenor($query): ?array;
}
