<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThemeRequest;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ThemeController extends Controller
{
    /**
     * Show the form for creating a new theme.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('admin.themes.create');
    }

    /**
     * Store a newly created theme in the database.
     *
     * @param  \App\Http\Requests\ThemeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ThemeRequest $request): RedirectResponse
    {
        Theme::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->route('admin.dashboard')->with('success', __('message.theme.success.create'));
    }

    /**
     * Show the form for editing the specified theme.
     *
     * @param  \App\Models\Theme  $theme
     * @return \Illuminate\View\View
     */
    public function edit(Theme $theme): View
    {
        return view('admin.themes.edit', compact('theme'));
    }

    /**
     * Update the specified theme in the database.
     *
     * @param  \App\Http\Requests\ThemeRequest  $request
     * @param  \App\Models\Theme  $theme
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ThemeRequest $request, Theme $theme): RedirectResponse
    {
        $theme->update([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->route('admin.dashboard')->with('success', __('message.theme.success.update'));
    }

    /**
     * Remove the specified theme from the database.
     *
     * @param  \App\Models\Theme  $theme
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Theme $theme): RedirectResponse
    {
        $theme->delete();

        return redirect()->route('admin.dashboard')->with('success', __('message.theme.success.delete'));
    }
}
