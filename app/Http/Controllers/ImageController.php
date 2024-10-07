<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Haal de afbeeldingen op met paginatie
        $images = Image::sortable()->paginate(10);

        // Retourneer de index view met de afbeeldingen
        return view('images', compact('images'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('images.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valideer de afbeeldingen
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Controleer of er bestanden zijn geüpload
        if ($request->hasFile('images')) {
            // Loop door elk bestand dat is geüpload
            foreach ($request->file('images') as $file) {
                // Haal het originele bestandsnaam, pad en extensie op
                $filePath = $file->store('uploads', 'public'); // Sla het bestand op in de 'public/uploads' map
                $fileName = $file->getClientOriginalName(); // Haal de originele bestandsnaam op
                $fileExtension = $file->getClientOriginalExtension(); // Haal de bestandsextensie op

                // Sla elke afbeelding op in de database
                Image::create([
                    'file_name' => $fileName, // De bestandsnaam
                    'file_path' => $filePath, // Het bestandspad waar de afbeelding is opgeslagen
                    'file_extension' => $fileExtension, // De extensie van het bestand
                    'user_id' => auth()->id(),  // Voeg de ID van de huidige gebruiker toe
                ]);
            }

            return back()->with('success', 'Images uploaded successfully.');
        }

        return back()->withErrors('Please upload valid images.');
    }




    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $image = Image::findOrFail($id);
        return response()->file(storage_path('app/public/' . $image->file_path));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image)
    {
        return view('images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the image by its ID
        $image = Image::findOrFail($id);

        // Validate the new image if one is provided
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Check if a new image file is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image from storage
            if ($image->file_path) {
                Storage::delete('public/' . $image->file_path);
            }

            // Store the new image and update the file path
            $filePath = $request->file('image')->store('images', 'public');
            $image->file_path = $filePath;
        }

        // Save the changes to the database
        $image->save();

        // Redirect back to the images index with a success message
        return redirect()->route('images.index')->with('success', 'Image updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {

        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}
