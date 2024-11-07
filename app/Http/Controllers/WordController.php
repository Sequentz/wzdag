<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWordRequest;
use App\Http\Requests\UpdateWordRequest;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $words = Word::sortable()->paginate(10);
        return view('words', compact('words'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('words.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWordRequest $request)
    {
        // Validate the request
        $validated = $request->validated();

        // Create the new word
        Word::create([
            'name' => $validated['name'],
        ]);

        // Redirect with a success message
        return redirect()->route('words.index')->with('success', 'Word created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Word $word)
    {
        return view('words.show', compact('word'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Word $word)
    {
        return view('words.edit', compact('word'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWordRequest $request, Word $word)
    {
        // Validate the input
        $validated = $request->validated();

        // Update the word
        $word->update([
            'name' => $validated['name'],
        ]);

        // Redirect with a success message
        return redirect()->route('words.index')->with('success', 'Word updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Word $word)
    {
        return redirect()->route('words.index')->with('success', 'Word deleted successfully.');
    }
}
