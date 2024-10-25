<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\Theme;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $themes = Theme::all();

        $posts = Post::where('publication_time', '<=', now())
            ->orderBy('publication_time', 'desc')
            ->paginate(15);

        return view('posts.index', compact('themes', 'posts'));
    }

    public function showByTheme($id): View
    {
        $posts = Post::where('theme_id', $id)
            ->where('publication_time', '<=', now())
            ->orderBy('publication_time', 'desc')
            ->paginate(15);

        $theme = Theme::findOrFail($id);

        return view('posts.theme', compact('posts', 'theme'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $themes = Theme::all();
        return view('admin.posts.create', compact('themes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $mediaPath = null;

        if ($request->media_type === 'image' && $request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('images', 'public');
        } elseif ($request->media_type === 'video' && $request->video_link) {
            $mediaPath = $request->video_link;
        }

        $publicationTime = null;
        if ($request->publication_time) {
            $publicationTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->publication_time, 'UTC')
                ->setTimezone('UTC');
        }

        Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'publication_time' => $publicationTime,
            'media' => $mediaPath,
            'user_id' => auth()->id(),
            'theme_id' => $request->theme_id,
        ]);

        return redirect()->route('admin')->with('success', 'Post créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {

        $user = auth()->user();
        // hacky, can't find better way
        if (!$user) {
            $user = new User([
                'role' => 'guest'
                ]
            );

        }
        if ($user->cannot('view', $post)) {
            abort(404);
        }

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        $themes = Theme::all();
        return view('admin.posts.edit', compact('post', 'themes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $mediaPath = $post->media;

        if ($post->media && Storage::disk('public')->exists($mediaPath) && (
                ($request->media_type === 'image' && $request->hasFile('media')) ||
                ($request->media_type === 'video' && $request->video_link)
            )) {
            Storage::disk('public')->delete($mediaPath);
        }

        if ($request->media_type === 'image' && $request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('images', 'public');
        } elseif ($request->media_type === 'video' && $request->video_link) {
            $mediaPath = $request->video_link;
        }

        $publicationTime = null;
        if ($request->publication_time) {
            $publicationTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->publication_time, 'UTC')
                ->setTimezone('UTC');
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'publication_time' => $publicationTime,
            'media' => $mediaPath,
            'user_id' => auth()->id(),
            'theme_id' => $request->theme_id,
        ]);

        return redirect()->route('admin')->with('success', 'Post mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
