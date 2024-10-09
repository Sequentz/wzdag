<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;
use App\Models\Image;

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

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $theme = Theme::create([
            'name' => $request->name,
        ]);


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filePath = $file->store('uploads', 'public');
                $fileName = $file->getClientOriginalName();
                $fileExtension = $file->getClientOriginalExtension();


                $theme->images()->create([
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_extension' => $fileExtension,
                ]);
            }
        }

        return redirect()->route('themes.index')->with('success', 'Theme created successfully with images.');
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


    public function edit($id)
    {
        $theme = Theme::find($id);

        if (!$theme) {
            return redirect()->route('themes.index')->withErrors('Theme not found.');
        }

        $images = $theme->images;

        return view('themes.edit', compact('theme', 'images'));
    }

    /**
     * Update the specified resource in storage.
     */ public function update(Request $request, Theme $theme)
    {
        // Validate data
        $request->validate([
            'name' => 'required|string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $theme->update(['name' => $request->name]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filePath = $file->store('uploads', 'public');
                $fileName = $file->getClientOriginalName();
                $fileExtension = $file->getClientOriginalExtension();


                $theme->images()->create([
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_extension' => $fileExtension,
                ]);
            }
        }

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

    public function destroyImage($imageId)
    {
        // Find the image
        $image = Image::findOrFail($imageId);

        // Detach the image from the themes it is associated with
        $image->themes()->detach();

        // Delete the image itself
        Storage::delete('public/' . $image->file_path);
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}
