<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\WorkoutVideoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!isset($this->id)) {
            return [];
        }
        return  [
            'id' => $this->id,
            'title' => $this->title,
            'category' => $this->category?->name,
            'category_id' => $this->category_id,
            'description' => $this?->description,
            'is_featured' => $this->is_featured,
            'video' => isset($this->video->id) ? WorkoutVideoResource::make($this->video) : null,
            'status' => $this->status,
            "progress" => $this->watchedVideo?->watched_time ? round(convertToSeconds($this->watchedVideo?->watched_time) / convertToSeconds($this->video?->duration) * 100) : 0,
            "watched_time" => $this->watchedVideo?->watched_time ?? "00:00:00",
            'published_time' => $this->created_at->diffForHumans(),
            'is_favorite' =>  boolval($this->isFavourited->count()),
            'images' => $this->whenLoaded('images')

        ];
    }
}
