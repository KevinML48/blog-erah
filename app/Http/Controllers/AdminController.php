<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        $users = $request->user()->orderBy('created_at', 'desc')->take(10)->get();
        $posts = Post::orderBy('created_at', 'desc')->take(10)->get();

        $unpublishedPosts = Post::where('publication_time', '>', now())
            ->orderBy('publication_time', 'asc')
            ->get();

        $themes = Theme::all();

        return view('admin.dashboard', compact('users', 'posts', 'unpublishedPosts', 'themes'));
    }

    public function deleteOrphanedContents(): RedirectResponse
    {
        $comments = Comment::all();

        foreach ($comments as $comment) {
            if (!$comment->contentExists() && $comment->replies) {
                $comment->delete();
            }
        }
        return redirect()->route('admin');
    }
}
