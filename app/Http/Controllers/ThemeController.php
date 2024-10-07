<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;
use App\Models\Image;

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
        // Valideer dat de naam van het thema aanwezig is en dat er afbeeldingen zijn geüpload
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Valideer dat de geüploade bestanden afbeeldingen zijn
        ]);

        // Maak het thema aan
        $theme = Theme::create(['name' => $validated['name']]);

        // Controleer of er bestanden zijn geüpload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                // Sla de afbeelding op in de 'uploads' map binnen 'public'
                $filePath = $file->store('uploads', 'public');
                // Haal de originele bestandsnaam op
                $fileName = $file->getClientOriginalName();
                // Haal de bestandsextensie op
                $fileExtension = $file->getClientOriginalExtension();

                // Maak een nieuwe afbeelding aan en sla deze op in de database
                $image = Image::create([
                    'file_name' => $fileName, // De originele bestandsnaam
                    'file_path' => $filePath, // Het bestandspad waar de afbeelding is opgeslagen
                    'file_extension' => $fileExtension, // De extensie van het bestand
                    'user_id' => auth()->id(),  // Koppel de afbeelding aan de ingelogde gebruiker (optioneel)
                ]);

                // Koppel de afbeelding aan het thema via de many-to-many relatie
                $theme->images()->attach($image->id);
            }
        }

        return redirect()->route('themes.index')->with('success', 'Theme and images uploaded successfully.');
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

        $theme->images()->detach();
        $theme->delete();

        return redirect()->route('themes.index')->with('success', 'Theme deleted successfully.');
    }
    public function getImages(Theme $theme)
    {
        return response()->json([
            'images' => $theme->images
        ]);
    }
}
