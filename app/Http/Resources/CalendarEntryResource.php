<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarEntryResource extends JsonResource
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
            'date_published' => Carbon::parse($this->date_published)->format('d-m-Y'),
            'title' => $this->title,
            'content' => $this->content,
            'slug' => Carbon::parse($this->slug)->format('d-m-Y'), 
            'highlighted' => $this->highlighted,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'categories' => CategoryResource::collection($this->whenLoaded('categories'))

        ];
    }
}
