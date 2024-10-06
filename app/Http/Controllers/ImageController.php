<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        if ($request->file('image')) {
            $file = $request->file('image');
            $filePath = $file->store('uploads', 'public');
            $fileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();


            $image = Image::create([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_extension' => $fileExtension,
                'user_id' => auth()->id(),
            ]);

            return back()->with('success', 'Image uploaded successfully.');
        }

        return back()->withErrors('Please upload a valid image.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //
    }
}
