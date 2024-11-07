<?php

namespace App\Http\Controllers;

use App\Models\CommentContent;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function deleteOrphanedContents(): RedirectResponse
    {
        $allContents = CommentContent::all();

        foreach ($allContents as $content) {
            if (!$content->comment) {
                $content->delete();
                Log::info('delete content');
            }
        }
        return redirect()->route('admin');
    }
}
