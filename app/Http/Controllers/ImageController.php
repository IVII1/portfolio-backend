<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageStoreRequest;
use App\Http\Requests\ImageUpdateRequest;
use App\Models\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = Image::all();
        return $images;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ImageStoreRequest $request)
    {
        $params = $request->all();
        $uploadedFile = $request->file('image');
        $filePath = $uploadedFile->store('gallery', 'public');
        $params['image_url'] = Storage::url($filePath);
        $image = Image::create($params);
        return $image;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try{
            $image = Image::findOrFail($id);
        }
        catch (ModelNotFoundException){
            return response()->json(['message' => 'Image not found'], 404);
        }
        return $image;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ImageUpdateRequest $request, int $id)
    {
        $params = $request->all();
            try{
            $image = Image::findOrFail($id);
        }
        catch (ModelNotFoundException){
            return response()->json(['message' => 'Image not found'], 404);
        }
        $image->update($params);
        return $image;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
            try{
            $image = Image::findOrFail($id);
        }
        catch (ModelNotFoundException){
            return response()->json(['message' => 'Image not found'], 404);
        }
        $filePath = str_replace('/storage/', '', $image['image_url']);
        Storage::disk('public')->delete($filePath);
        $image->delete();
        return response()->json(['message' => 'Image deleted'], 204);

    }
}
