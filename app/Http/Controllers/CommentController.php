<?php

namespace App\Http\Controllers;

use App\Models\CommentContent;
use App\Models\CommentStructure;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $commentContent = CommentContent::create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        CommentStructure::create([
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'content_id' => $commentContent->id,
        ]);

        return redirect()->route('posts.show', $post->id)->with('success', 'Commentaire ajouté.');
    }

    public function show(Post $post, CommentStructure $comment)
    {
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        $comments = CommentStructure::with(['content.user', 'replies.content.user'])
            ->where('id', $comment->id)
            ->get();

        return view('posts.show', compact('post', 'comments', 'comment'));
    }



}
