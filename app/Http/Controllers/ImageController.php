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

        $images = Image::sortable()->paginate(10);
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

        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $file) {

                $filePath = $file->store('uploads', 'public');
                $fileName = $file->getClientOriginalName();
                $fileExtension = $file->getClientOriginalExtension();

                Image::create([
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_extension' => $fileExtension,
                    'user_id' => auth()->id(),
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

        $image = Image::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('image')) {

            if ($image->file_path) {
                Storage::delete('public/' . $image->file_path);
            }


            $filePath = $request->file('image')->store('images', 'public');
            $image->file_path = $filePath;
        }


        $image->save();
        return redirect()->route('images.index')->with('success', 'Image updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        // Detach the image from the theme(s) it belongs to, without deleting the theme itself
        $image->themes()->detach();

        // Delete the image file from storage
        Storage::delete('public/' . $image->file_path); // Make sure the path is 'public/'

        // Now delete the image itself
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}
