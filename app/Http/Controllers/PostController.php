<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.posts.create');
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
        if ($request->publicationTime) {
            $publicationTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->publication_time, 'UTC')
                ->setTimezone('UTC');
        }

        Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'publication_time' => $publicationTime,
            'media' => $mediaPath,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('admin')->with('success', 'Post créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        return view('admin.posts.edit', compact('post'));
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
        if ($request->publicationTime) {
            $publicationTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->publication_time, 'UTC')
                ->setTimezone('UTC');
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'publication_time' => $publicationTime,
            'media' => $mediaPath,
            'user_id' => auth()->id(),
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
