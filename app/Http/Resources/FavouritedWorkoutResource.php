<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\WorkoutResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FavouritedWorkoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "Latest_Workout" =>  WorkoutResource::collection($this),
        ];
    }
}
