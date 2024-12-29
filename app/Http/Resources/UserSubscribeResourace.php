<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSubscribeResourace extends JsonResource
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
            'name' => $this->package->name,
            'duration' => $this->package->duration,
            'price' => $this->package->price,
            'in_app_id' => $this->in_app_id,
            'expire_date' => $this->expire_date->format('F d Y'),
            'is_active' => $this->is_active,
            'package_is_active' => $this->package->is_active,
            'purchase_date' => $this->created_at->format('F d Y'),
        ];
    }
}
