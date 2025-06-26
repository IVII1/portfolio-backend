<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarEntryStoreRequest;
use App\Http\Requests\CalendarEntryUpdateRequest;
use App\Http\Resources\CalendarEntryResource;
use App\Models\CalendarEntry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CalendarEntry::with(['categories', 'references']);
        $allowedSortColumns = ['title', 'created_at', 'date_published', 'highlighted', 'type'];
        $year = $request->get('year');
        $month = $request->get('month');
        $categories = $request->get('categories');
        $search = $request->get('q');
        $highlighted = $request->get('highlighted');
        $type = $request->get('type');

        $sortColumn = $request->input('sort', 'date_published');
        $direction = $request->input('order', 'asc');


        if ($sortColumn === 'date_published') {
            $direction = 'asc';
        } else {
            $direction = 'desc';
        }


        if (!in_array(strtolower($direction), ['asc', 'desc'])) {
            $direction = 'desc';
        }


        if (in_array($sortColumn, $allowedSortColumns)) {
            $query->orderBy($sortColumn, $direction);
        } else {
            $query->orderBy('date_published', 'asc');
        }

        if ($year && $month) {
            if ($year > Carbon::now()->year) {
                return response()->json(['message' => 'Invalid year'], 400);
            }
            if ($month < 1 || $month > 12) {
                return response()->json(['message' => 'Invalid month'], 400);
            }

            $query->whereYear('date_published', $year)
                ->whereMonth('date_published', $month);
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
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('content', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($highlighted === 'true') {
            $query->where('highlighted', true);
        }
        if ($type === 'manual' || $type === 'automated') {
            $query->where('type', $type);
        }


        $calendarEntries = $query->paginate(31);

        return CalendarEntryResource::collection($calendarEntries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CalendarEntryStoreRequest $request)
    {
        $params = $request->all();
        $params['slug'] = Carbon::parse($params['date_published'])->format('d-m-Y');
        $calendarEntry = CalendarEntry::create($params);
        if ($request->has('category_ids')) {
            $calendarEntry->categories()->attach($request->category_ids);
        }
        if ($request->has('reference_ids')) {
            $calendarEntry->references()->attach($request->reference_ids);

        }
        $calendarEntry->load(['references', 'categories']);
        return new CalendarEntryResource($calendarEntry);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        try {
            $calendarEntry = CalendarEntry::with(['categories', 'references'])
                ->where('slug', $slug)
                ->firstOrFail();

            return new CalendarEntryResource($calendarEntry);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Calendar entry not found'], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
 public function update(CalendarEntryUpdateRequest $request, string $slug)
{
    $calendarEntry = CalendarEntry::where('slug', $slug)->firstOrFail();
    $params = $request->all();
    
    DB::transaction(function () use ($calendarEntry, $request, $params) {
        if ($request->has('category_ids')) {
            $calendarEntry->categories()->syncWithoutDetaching($request->category_ids);
        }
        
        if ($request->has('reference_ids')) {
            $existingReferenceIds = $calendarEntry->references()->pluck('references.id')->toArray();
            
            $newReferenceIds = array_diff($request->reference_ids, $existingReferenceIds);
            
            $calendarEntry->references()->syncWithoutDetaching($request->reference_ids);
            
           
            if (!empty($newReferenceIds)) {
                $this->updateReferenceCounts($newReferenceIds);
            }
        }
        
        if ($request->has('remove_category_ids')) {
            $calendarEntry->categories()->detach($request->remove_category_ids);
        }
        
        
        if ($request->has('remove_reference_ids')) {
            $calendarEntry->references()->detach($request->remove_reference_ids);
            
           
            $this->updateReferenceCounts($request->remove_reference_ids);
        }
        
        $calendarEntry->update($params);
    });
    
    $calendarEntry->load(['categories', 'references']);
    return new CalendarEntryResource($calendarEntry);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $calendarEntry = CalendarEntry::where('slug', $slug)->firstOrFail();
        $calendarEntry->delete();
        return response()->json(['message' => 'Entry Deleted Successfully']);
    }
    private function updateReferenceCounts(array $referenceIds)
{
    foreach ($referenceIds as $referenceId) {
        DB::table('references')
            ->where('id', $referenceId)
            ->update([
                'count' => DB::table('calendar_entry_reference')
                    ->where('reference_id', $referenceId)
                    ->count(),
                'updated_at' => now()
            ]);
    }
}
}
