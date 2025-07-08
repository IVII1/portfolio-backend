<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return $projects;
    }



    /**
     * Store a newly created resource in storage.
     */
   public function store(ProjectStoreRequest $request)
{
    $params = $request->all();
    $owner = $params['owner'];
    $repo = $params['repo'];
    $githubRepo = GitHub::repo()->show($owner, $repo);
    $contributorInfo = GitHub::repo()->contributors($owner, $repo);
    $params['slug'] = Str::slug($params['title']);
    $params['github_url'] = $githubRepo['html_url'];
    $params['date_started'] = $githubRepo['created_at'];
    $params['language'] = $githubRepo['language'];
    $targetLogin = 'IVII1';
    $targetIndex = null;
    foreach ($contributorInfo as $index => $contributor) {
        if (isset($contributor['login']) && $contributor['login'] === $targetLogin) {
            $targetIndex = $index;
            break;
        }
    }
      $contributors = [];
    foreach ($contributorInfo as $contributor) {
        if (isset($contributor['login'])) {
            $contributors[] = $contributor['login'];
        }
    }
$totalCommits = 0;
foreach($contributorInfo as $contributor){
    if (isset($contributor['contributions'])) {
        $totalCommits += $contributor['contributions'];
    }
}
$params['total_commit_count'] = $totalCommits;

    $params['contributors'] = $contributors;
    $params['personal_commit_count'] = $targetIndex !== null ? $contributorInfo[$targetIndex]['contributions'] : 0;
    $gallery = $request->file('gallery', []);
    unset($params['gallery']);

    $project = Project::create($params);

    foreach ($gallery as $uploadedFile) {
        $path = $uploadedFile->store('gallery', 'public');

        $project->images()->create([
            'image_link' => Storage::url($path),
            'slug' => null,
            'caption' => null, 
        ]);
    }

    $project->load('images');
    return new ProjectResource($project);
}

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
              try {
            $project = Project::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Project Not Found'], 404);
        }
        $project->load('images');
        return new ProjectResource($project);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectUpdateRequest $request, string $slug)
    {
        $params = $request->all();
         try {
            $project = Project::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Project Not Found'], 404);
        }
        $project->update($params);
        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(string $slug)
{
    try {
            $project = Project::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Project Not Found'], 404);
        }
    

     foreach ($project->images as $image) {
         Storage::disk('public')->delete(str_replace('/storage/', '', $image->image_link));
         $image->delete();
     }

    $project->delete();

    return response()->json(['message' => 'Project and related images deleted.'], 200);
}



    public function data()
    {
        return  GitHub::repo()->contributors('IVII1', 'mara-frontend');
    }
}
