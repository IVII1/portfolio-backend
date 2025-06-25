<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReferenceUpdateRequest;
use App\Http\Resources\ReferenceResource;
use App\Models\Reference;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reference::with('entries', 'categories');

        $allowedSortColumns = ['count', 'created_at'];
        $sortColumn = $request->input('sort', 'id');
        $direction = $request->input('order', 'desc');

        if (!in_array(strtolower($direction), ['asc', 'desc'])) {
            $direction = 'desc';
        }

        if (in_array($sortColumn, $allowedSortColumns)) {

            $query->orderBy($sortColumn, $direction);
        } else {
            $query->orderBy('id', 'asc');
        }




        $references = $query->get();

        return ReferenceResource::collection($references);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $params = $request->all();
        $reference = Reference::create($params);
        if ($request->has('category_ids')) {
            $reference->categories()->sync($request->category_ids);
        }
        if ($request->has('entry_ids')) {
            $reference->entries()->sync($request->entry_ids);
        }
        $reference->load(['entries', 'categories']);

        return new ReferenceResource($reference);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $reference = Reference::findOrFail($id);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Reference Not Found'], 404);
        }

        return new ReferenceResource($reference);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(ReferenceUpdateRequest $request, int $id)
    {
        try {
            $reference = Reference::findOrFail($id);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Reference Not Found'], 404);
        }


        $params = $request->all();
        $reference->update($params);


        if ($request->has('category_ids')) {
            $reference->categories()->sync($request->category_ids);
        }
        if ($request->has('entry_ids')) {
            $reference->entries()->sync($request->entry_ids);
            $reference->update(['count' => $reference->entries()->count()]);
        }


        $reference->load(['categories', 'entries']);

        return new ReferenceResource($reference);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $reference = Reference::findOrFail($id);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Reference Not Found'], 404);
        }
        $reference->delete();
        return response()->json(['message' => 'Entry Deleted Successfully']);
    }
}
