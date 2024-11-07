<?php

namespace App\Http\Controllers;

use App\Models\Puzzle;
use Illuminate\Http\Request;
use App\Models\Word;
use App\Models\Theme;
use App\Models\Image;

use App\Http\Requests\StorePuzzleRequest;


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

    public function store(StorePuzzleRequest $request)
    {
        $validated = $request->validated();


        $puzzle = new Puzzle();
        $puzzle->name = $validated['name'];
        $puzzle->theme_id = $validated['theme_id'];


        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');

            $image = Image::create([
                'file_path' => $imagePath,
                'file_name' => $request->file('image')->getClientOriginalName(),
                'file_extension' => $request->file('image')->getClientOriginalExtension(),
            ]);
            $puzzle->image_id = $image->id;
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

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
}
