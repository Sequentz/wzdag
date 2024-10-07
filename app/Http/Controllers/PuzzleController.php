<?php

namespace App\Http\Controllers;

use App\Models\Puzzle;
use Illuminate\Http\Request;
use App\Models\Word;
use App\Models\Theme;
use App\Models\Image;


class PuzzleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $puzzles = Puzzle::sortable()->paginate(10);
        return view('puzzles', compact('puzzles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $themes = Theme::all();
        return view('puzzles.create', compact('themes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valideer de puzzel en de woorden
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'theme_id' => 'required|exists:themes,id',
            'words' => 'required|array|min:1',
            'words.*' => 'string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Optionele afbeelding
        ]);

        // Maak een nieuwe puzzel aan
        $puzzle = new Puzzle();
        $puzzle->name = $validated['name'];
        $puzzle->theme_id = $validated['theme_id'];

        // Opslaan van de afbeelding als deze is geüpload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $puzzle->image_id = Image::create(['file_path' => $imagePath])->id;
        }

        $puzzle->save();

        foreach ($validated['words'] as $wordText) {
            $word = Word::firstOrCreate(['word' => $wordText]);
            $puzzle->words()->attach($word->id);
        }

        return redirect()->route('puzzles.index')->with('success', 'Puzzle successfully created.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Puzzle $puzzle)
    {

        $theme = $puzzle->theme;
        $image = $puzzle->image;
        $words = $puzzle->words;


        return view('puzzles.show', compact('puzzle', 'theme', 'image', 'words'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Puzzle $puzzle)
    {
        // Haal alle thema's op
        $themes = Theme::all();

        // Haal alle afbeeldingen van het geselecteerde thema van de puzzel
        $images = $puzzle->theme->images; // Zorg ervoor dat de relatie correct is ingesteld

        // Retourneer de view met de puzzel, thema's en afbeeldingen
        return view('puzzles.edit', compact('puzzle', 'themes', 'images'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Puzzle $puzzle)
    {
        // Valideer de data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'theme_id' => 'required|exists:themes,id',
            'image_id' => 'required|exists:images,id', // Zorg ervoor dat de afbeelding bestaat
            'words' => 'array|min:1',
            'words.*' => 'string|max:255',
        ]);

        // Update puzzelgegevens
        $puzzle->update([
            'name' => $validated['name'],
            'theme_id' => $validated['theme_id'],
            'image_id' => $validated['image_id'], // Update de gekoppelde afbeelding
        ]);

        // Update woorden
        $puzzle->words()->detach();
        foreach ($validated['words'] as $wordText) {
            $word = Word::firstOrCreate(['word' => $wordText]);
            $puzzle->words()->attach($word->id);
        }

        return redirect()->route('puzzles.index')->with('success', 'Puzzle updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Puzzle $puzzle)
    {
        $puzzle->delete();
        return redirect()->route('puzzles.index')->with('success', 'Puzzle deleted successfully.');
    }
}
