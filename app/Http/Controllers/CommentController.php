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
        $request->validate([
            'body' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:comments_structure,id',
        ]);

        $commentContent = CommentContent::create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        CommentStructure::create([
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'content_id' => $commentContent->id,
        ]);

        return redirect()->route('posts.show', $post->id)->with('success', 'Comment added successfully.');
    }


    public function index(Post $post)
    {
        $comments = CommentStructure::with(['content', 'replies.content'])
            ->where('post_id', $post->id)
            ->whereNull('parent_id')
            ->get();

        return response()->json($comments);
    }

}
