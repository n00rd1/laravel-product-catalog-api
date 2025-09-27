<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'price' => number_format($this->price, 2, '.', ''),
            'quantity' => $this->quantity,
            'properties' => $this->whenLoaded('propertyValues', function () {
                return $this->propertyValues->map(function ($propertyValue) {
                    return [
                        'name' => $propertyValue->property->name,
                        'value' => $propertyValue->value,
                    ];
                });
            }),
        ];
    }
}
