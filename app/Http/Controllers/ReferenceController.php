<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReferenceStoreRequest;
use App\Http\Requests\ReferenceUpdateRequest;
use App\Http\Resources\ReferenceResource;
use App\Models\Reference;
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
        $categories = $request->get('categories');
        $search = $request->get('q');

      
        if (!in_array(strtolower($direction), ['asc', 'desc'])) {
            $direction = 'desc';
        }

        if (in_array($sortColumn, $allowedSortColumns)) {
            $query->orderBy($sortColumn, $direction);
        } else {
            $query->orderBy('id', 'asc');
        }

        
        if ($categories) {
            if (is_string($categories)) {
                $categories = explode(',', $categories);
            }
            
            $query->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('name', $categories);
            });
        }

 
        if ($search) {
            $searchTerm = trim($search);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('source_url', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('source_name', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Paginate results
        $references = $query->paginate(15);

        return ReferenceResource::collection($references);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(ReferenceStoreRequest $request)
    {

        $params = $request->all();
        $reference = Reference::create([
            'source_name' => $params['source_name'],
            'source_url' => $params['source_url'],
            'count' => 0
        ]);
        if ($request->has('category_ids')) {
            $reference->categories()->attach($request->category_ids);
        }
        if ($request->has('entry_ids')) {
            $reference->entries()->attach($request->entry_ids);
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
            $reference = Reference::with(['entries', 'categories'])->findOrFail($id);
            return new ReferenceResource($reference);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Reference not found'], 404);
        }
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
            $reference->categories()->syncWithoutDetaching($request->category_ids);
        }
        if ($request->has('entry_ids')) {
            $reference->entries()->syncWithoutDetaching($request->entry_ids);
            $reference->update(['count' => $reference->entries()->count()]);
        }
        if ($request->has('remove_category_ids')) {
            $reference->categories()->detach($request->remove_category_ids);
        }
        if ($request->has('remove_entry_ids')) {
            $reference->entries()->detach($request->remove_entry_ids);
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
        return response()->json(['message' => 'Reference Deleted Successfully']);
    }
}
