<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ReferenceResource;
use App\Models\Category;
use App\Models\Reference;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
     
        return CategoryResource::collection($categories);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $params = $request->all();
        $category = Category::create($params);
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $name)
    {
        try {
            $category = Category::where('name', $name)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category Not Found'], 404);
        }
        return new CategoryResource($category);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, string $name)
    {
        try {
            $category = Category::where('name', $name)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category Not Found'], 404);
        }

        $params = $request->all();

        $category->update($params);
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $name)
    {
         try {
            $category = Category::where('name', $name)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category Not Found'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}
