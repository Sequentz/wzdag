<?php

namespace App\Http\Controllers;

use App\Models\Puzzle;
use Illuminate\Http\Request;
use App\Models\Word;


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
        return view('puzzles.create');
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
        ]);

        $puzzle = new Puzzle();
        $puzzle->name = $validated['name'];
        $puzzle->theme_id = $validated['theme_id'];
        $puzzle->save();


        foreach ($validated['words'] as $word) {
            $word = Word::firstOrCreate(['word' => $word]);
            $puzzle->words()->attach($word->id);
        }
        return redirect()->route('puzzles.index')->with('success', 'Puzzle successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Puzzle $puzzle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Puzzle $puzzle)
    {
        return view('puzzles.edit', compact('puzzle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Puzzle $puzzle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'theme_id' => 'required|exists:themes,id',
            'words' => 'required|array|min:1',
            'words.*' => 'string|max:255',
        ]);

        $puzzle->update([
            'name' => $validated['name'],
            'theme_id' => $validated['theme_id'],
        ]);

        // Update woorden
        $puzzle->words()->detach();
        foreach ($validated['words'] as $word) {
            $word = Word::firstOrCreate(['word' => $word]);
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
