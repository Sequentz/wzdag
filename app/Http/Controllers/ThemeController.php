<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;
use App\Http\Requests\StoreThemeRequest;
use App\Http\Requests\UpdateThemeRequest;
use Illuminate\Support\Facades\Storage;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $themes = Theme::sortable()->paginate(10);
        return view('themes', compact('themes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('themes.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreThemeRequest $request)
    {

        $validated = $request->validated();
        Theme::create([
            'name' => $validated['name'],
        ]);

        return redirect()->route('themes.index')->with('success', 'Theme created successfully.');
    }


    public function show(Theme $theme)
    {
        return view('themes.show', compact('theme'));
    }

    public function edit($id)
    {

        return view('themes.edit', compact('theme'));
    }


    public function update(UpdateThemeRequest $request, Theme $theme)
    {

        $validated = $request->validated();
        $theme->update([
            'name' => $validated['name'],
        ]);


        return redirect()->route('themes.index')->with('success', 'Theme updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme)
    {
        return redirect()->route('themes.index')->with('success', 'Theme deleted successfully.');
    }
}
