<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutBaseCategory extends JsonResource
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
        return [
            'id' => $this?->id,
            'name' => $this->name,
            "workouts" => $this->workouts?->take(5)->map(function ($workout) {
                return [
                    'id' => $workout?->id,
                    'title' => $workout->title,
                    'category_id' => $workout->category_id,
                    'description' => $workout->description,
                    'video' => WorkoutVideoResource::make($workout->video),
                    "progress" => $workout->watchedVideo?->watched_time ? round(convertToSeconds($workout->watchedVideo?->watched_time) / convertToSeconds($workout->video?->duration) * 100) : 0,
                    "watched_time" => $workout->watchedVideo?->watched_time ?? "00:00:00",
                    // "watchedVideo" => $workout->watchedVideo,
                    'published_time' => $workout->created_at->diffForHumans(),
                    'is_favorite' => boolval($workout->isFavourited->count()),
                ];
            })
        ];
    }
}
