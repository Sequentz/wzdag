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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'theme_id' => 'required|exists:themes,id',
            'words' => 'required|array|min:1',
            'words.*' => 'string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [
            'name.required' => 'The name field is required.',
            'theme_id.required' => 'The theme id field is required.',
            'theme_id.exists' => 'The selected theme is invalid.',
            'words.0.string' => 'The first word must be a string.',
            'words.1.string' => 'The second word must be a string.',
            'words.2.string' => 'The third word must be a string.',
            'words.*.string' => 'Each word must be a valid string.',
        ]);

        // Create a new Puzzle instance
        $puzzle = new Puzzle();
        $puzzle->name = $validated['name'];
        $puzzle->theme_id = $validated['theme_id'];

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            // Create an image record and associate it with the puzzle
            $image = Image::create([
                'file_path' => $imagePath,
                'file_name' => $request->file('image')->getClientOriginalName(),  // Optional: Store original file name
                'file_extension' => $request->file('image')->getClientOriginalExtension(),  // Optional: Store file extension
            ]);
            $puzzle->image_id = $image->id;  // Assign image ID to the puzzle
        }

        // Save the puzzle
        $puzzle->save();

        // Attach words to the puzzle
        foreach ($validated['words'] as $wordText) {
            $word = Word::firstOrCreate(['word' => $wordText]);  // Create the word if it doesn't exist
            $puzzle->words()->attach($word->id);  // Attach word to the puzzle
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

        $themes = Theme::all();
        $images = $puzzle->theme->images;

        return view('puzzles.edit', compact('puzzle', 'themes', 'images'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Puzzle $puzzle)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'theme_id' => 'required|exists:themes,id',
            'image_id' => 'required|exists:images,id',
            'words' => 'array|min:1',
            'words.*' => 'string|max:255',
        ]);

        $puzzle->update([
            'name' => $validated['name'],
            'theme_id' => $validated['theme_id'],
            'image_id' => $validated['image_id'],
        ]);


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
