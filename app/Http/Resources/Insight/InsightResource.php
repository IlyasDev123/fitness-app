<?php

namespace App\Http\Resources\Insight;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isEmpty;

class InsightResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'duration' => $this->duration,
            'category_id' => $this->category_id,
            'category' => $this->category?->name,
            'short_description' => $this->short_description,
            'description' => $this->description,
            // 'is_featured' => $this->is_featured,
            'status' => $this->status,
            'thumbnail' => $this->thumbnail,
            'likes' => $this?->likes_count,
            'is_liked' => $this->isLiked->isNotEmpty(),
            'is_favourited' => $this->isFavourited->isNotEmpty(),
            'created_at' => $this->created_at,
        ];
    }
}
