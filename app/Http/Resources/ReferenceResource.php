<?php

namespace App\Http\Resources;

use App\Models\CalendarEntry;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferenceResource extends JsonResource
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
            'source_name' => $this->source_name,
            'source_url' => $this->source_url,
            'count' => $this->count,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'entries' => CalendarEntryResource::collection($this->whenLoaded('entries')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories'))
        ];
    }
}
