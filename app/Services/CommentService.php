<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\CommentContent;
use App\Models\Post;
use Illuminate\Contracts\Auth\Authenticatable;
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
        return Comment::with([
            'content' => function ($query) {
                $query->withCount('likes') // Preload likes count
                ->with('user'); // Preload the user who created the content
            },
            'content.user', // Preload user for the content
            'replies' => function ($query) {
                $query->with([
                    'content' => function ($query) {
                        $query->withCount('likes')
                            ->with('user');
                    },
                    'content.user',
                ])
                    ->withCount('replies');
            },
            'parent' => function ($query) {
                $query->withCount('replies');
            },
            'parent.content' => function ($query) {
                $query->withCount('likes')
                    ->with('user');
            },
        ])
            ->withCount('replies')
            ->where('id', $comment->id)
            ->paginate(1);
    }


    public function loadPostComments(Post $post): LengthAwarePaginator
    {
        return Comment::with([
            'content' => function ($query) {
                $query->withCount('likes') // Preload likes count
                ->with('user'); // Preload the user who created the content
            },
            'content.user', // Preload user for the content
            'replies' => function ($query) {
                $query->limit(2)->with([
                    'content' => function ($query) {
                        $query->withCount('likes')
                            ->with('user');
                    },
                    'content.user',
                ])
                    ->withCount('replies');
            }
        ])
            ->where('post_id', $post->id)
            ->withCount('replies')
            ->whereNull('parent_id')
            ->paginate(5);
    }

    public function addAuthUserTags(array $commentsLists, Authenticatable $authUser): void
    {
        // Extract relevant comment and user IDs
        $commentIds = [];
        $userIds = [];

        foreach ($commentsLists as $comments) {
            $comments->each(function ($comment) use (&$commentIds, &$userIds) {
                if ($comment->content) {
                    // Collect the content IDs of all comments
                    $commentIds[] = $comment->content->id;

                    // Collect the user IDs of all users who posted these comments
                    $userIds[] = $comment->content->user_id;
                }

                // Collect reply content IDs if they exist
                if ($comment->relationLoaded('replies')) {
                    $comment->replies->each(function ($reply) use (&$commentIds, &$userIds) {
                        if ($reply->content) {
                            $commentIds[] = $reply->content->id;
                            $userIds[] = $reply->content->user_id;
                        }
                    });
                }

                // Collect parent comment content IDs if they exist
                if ($comment->relationLoaded('parent') && $comment->parent !== null) {
                    $commentIds[] = $comment->parent->content->id;
                    $userIds[] = $comment->parent->content->user_id;
                }
            });
        }

        // Remove duplicates from the IDs arrays
        $commentIds = array_unique($commentIds);
        $userIds = array_unique($userIds);

        // Fetch likes for the relevant comment IDs
        $likedContentIds = $authUser->likes()->whereIn('likeable_id', $commentIds)->pluck('likeable_id')->toArray();

        // Fetch follows for the relevant user IDs
        $followedUserIds = $authUser->follows()->whereIn('followed_id', $userIds)->pluck('followed_id')->toArray();

        // Now, iterate over the comments and mark liked/followed status
        foreach ($commentsLists as $comments) {
            $comments->each(function ($comment) use ($authUser, $likedContentIds, $followedUserIds) {
                if ($comment->content) {
                    // Check if the comment's content is liked
                    $comment->content->is_liked_by_auth_user = in_array($comment->content->id, $likedContentIds);

                    // Check if the comment's user is followed
                    if ($authUser->id !== $comment->content->user_id) {
                        $comment->content->user->is_followed_by_auth_user = in_array($comment->content->user_id, $followedUserIds);
                    }
                }

                // Check each reply
                if ($comment->relationLoaded('replies')) {
                    $comment->replies->each(function ($reply) use ($likedContentIds, $followedUserIds) {
                        if ($reply->content) {
                            $reply->content->is_liked_by_auth_user = in_array($reply->content->id, $likedContentIds);

                            // Check if the reply's user is followed
                            $reply->content->user->is_followed_by_auth_user = in_array($reply->content->user_id, $followedUserIds);
                        }
                    });
                }

                // Check the parent comment
                if ($comment->relationLoaded('parent') && $comment->parent !== null) {
                    $comment->parent->content->is_liked_by_auth_user = in_array($comment->parent->content->id, $likedContentIds);

                    // Check if the parent comment's user is followed
                    $comment->parent->content->user->is_followed_by_auth_user = in_array($comment->parent->content->user_id, $followedUserIds);
                }
            });
        }
    }

    public function loadMoreComments(Post $post, $currentPage, array $existingCommentIds): LengthAwarePaginator
    {
        $comments = Comment::with([
            'content' => function ($query) {
                $query->withCount('likes') // Preload likes count
                ->with('user'); // Preload the user who created the content
            },
            'content.user', // Preload user for the content
            'replies' => function ($query) {
                $query->limit(2)->with([
                    'content' => function ($query) {
                        $query->withCount('likes')
                            ->with('user');
                    },
                    'content.user',
                ])
                    ->withCount('replies');
            }
        ])
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
        $comments = $comment->replies()->with([
            'content' => function ($query) {
                $query->withCount('likes') // Preload likes count
                ->with('user'); // Preload the user who created the content
            },
            'content.user', // Preload user for the content
            'replies' => function ($query) {
                $query->limit(2)->with([
                    'content' => function ($query) {
                        $query->withCount('likes')
                            ->with('user');
                    },
                    'content.user',
                ])
                    ->withCount('replies');
            }
        ])
            ->paginate(5, ['*'], 'page', $currentPage + 1);

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
