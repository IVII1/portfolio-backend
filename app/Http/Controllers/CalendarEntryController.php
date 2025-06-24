<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarEntryStoreRequest;
use App\Http\Requests\CalendarEntryUpdateRequest;
use App\Http\Resources\CalendarEntryResource;
use App\Models\CalendarEntry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CalendarEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CalendarEntry::with('categories')->orderBy('date_published', 'asc');

        $year = $request->get('year');
        $month = $request->get('month');
        $categories = $request->get('categories');
        $search = $request->get('q');
        $highlighted = $request->get('highlighted');
        $type = $request->get('type');


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
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%");
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
        $params['slug'] = $params['date_published'];
        $calendarEntry = CalendarEntry::create($params);
        if ($request->has('category_ids')) {
            $calendarEntry->categories()->sync($request->category_ids);
        }
        $calendarEntry->load('categories');
        return new CalendarEntryResource($calendarEntry);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        try {
            $calendarEntry = CalendarEntry::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Entry Not Found'], 404);
        }
        return new CalendarEntryResource($calendarEntry);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CalendarEntryUpdateRequest $request, string $slug)
    {
        $calendarEntry = CalendarEntry::where('slug', $slug)->firstOrFail();
        $params = $request->all();
        if ($request->has('category_ids')) {
            $calendarEntry->categories()->sync($request->category_ids);
        }
        $calendarEntry->update($params);
        $calendarEntry->load('categories');
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
}
