<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentContent;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $commentContent = CommentContent::create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        Comment::create([
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'content_id' => $commentContent->id,
        ]);

        return redirect()->route('posts.show', $post->id)->with('success', 'Commentaire ajouté.');
    }

    public function show(Post $post, Comment $comment)
    {
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        $comments = Comment::with(['content.user', 'replies.content.user'])
            ->where('id', $comment->id)
            ->paginate(5);

        return view('posts.show', compact('post', 'comments', 'comment'));
    }

    public function loadMoreComments(Post $post, Request $request)
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

    public function loadMoreReplies(Comment $comment, Request $request)
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

    public function destroy($id)
    {
        $commentContent = CommentContent::findOrFail($id);

        if (Auth::user()->id !== $commentContent->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $commentStructure = $commentContent->structure;

        $commentContent->delete();

        if (!$commentStructure->contentExists()) {
            $this->deleteEmptyParentStructures($commentStructure);
        }

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

    protected function deleteEmptyParentStructures(Comment $structure)
    {
        $parentStructure = $structure->parent;

        while ($parentStructure) {
            if (!$parentStructure->contentExists()) {
                $parentStructure->delete();
                $parentStructure = $parentStructure->parent;
            } else {
                break;
            }
        }
    }

}
