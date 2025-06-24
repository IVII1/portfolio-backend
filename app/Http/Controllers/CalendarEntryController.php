<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarEntryStoreRequest;
use App\Http\Requests\CalendarEntryUpdateRequest;
use App\Http\Resources\CalendarEntryResource;
use App\Models\CalendarEntry;
use Illuminate\Http\Request;

class CalendarEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $calenarEntries = CalendarEntry::all();
        return $calenarEntries;
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
        $calendarEntry = CalendarEntry::where('slug', $slug)->firstOrFail();
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
        return $calendarEntry;
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
