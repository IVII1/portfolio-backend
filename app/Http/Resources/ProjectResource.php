<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
           return [
            'id' => $this->id,
            'owner' => $this->owner,
            'repo' => $this->repo,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'purpose' => $this->purpose,
            'type' => $this->type,
            'live_url' => $this->live_url,  
            'slug' => $this->slug,
            'github_url' => $this->github_url,
            'date_started' => $this->date_started->format('m-d-Y'),
            'contributors' => $this->contributors,
            'language' => $this->language,
            'personal_commit_count' => $this->personal_commit_count,
            'total_commit_count' => $this->total_commit_count,
            'gallery' => $this->whenLoaded('images'),
            'challenges' => $this->challenges,
            'features' => $this->features,
            'key_takeaways' => $this->key_takeaways,
            'stack' => $this->stack,
            
          
        ];
    }
}
