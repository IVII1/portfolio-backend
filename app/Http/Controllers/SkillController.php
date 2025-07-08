<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkillStoreRequest;
use App\Http\Requests\SkillUpdateRequest;
use App\Http\Requests\StoreSkillRequest;
use App\Models\Skill;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::all();
        return $skills;
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(SkillStoreRequest $request)
    {
        $params = $request->all();
        $skill = Skill::create($params);
        return $skill;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $skill = Skill::findOrFail($id);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Skill not found']);
        };

        return $skill;
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, SkillUpdateRequest $request)
    {
        $params = $request->all();
        try {
            $skill = Skill::findOrFail($id);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Skill not found']);
        };
        $skill->update($params);

        return $skill;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
         try {
            $skill = Skill::findOrFail($id);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Skill not found']);
        };
        $skill->delete();
        return response()->json(['message' => 'Skill Deleted Successfully']);
    }
}
