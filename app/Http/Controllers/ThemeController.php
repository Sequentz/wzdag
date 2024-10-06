<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $themes = Theme::sortable()->paginate(10);

        // Retourneer de index view met de afbeeldingen
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
    public function store(Request $request)
    {
        // Valideer het thema
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Maak een nieuw thema aan
        $theme = new Theme();
        $theme->name = $validated['name'];
        $theme->save();

        // Redirect naar de themes index met een succesbericht
        return redirect()->route('themes.index')->with('success', 'Theme successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Theme $theme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Theme $theme)
    {
        return view('themes.edit', compact('theme'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Theme $theme)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $theme->update($validated);

        return redirect()->route('themes.index')->with('success', 'Theme updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme)
    {
        $theme->delete();
        return redirect()->route('themes.index')->with('success', 'Theme deleted successfully.');
    }
}
