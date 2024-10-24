<?php

namespace App\Http\Controllers;

use App\Models\Post;
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

        return view('admin.dashboard', compact('users', 'posts', 'unpublishedPosts'));
    }
}
