<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesReportResource extends JsonResource
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
            'order_id' => new SalesReportResource($this->whenLoaded('order')),
            'menu_item_id' => new SalesReportResource($this->whenLoaded('menu_item')),
            'customer_id' => new SalesReportResource($this->whenLoaded('customer')),
            'created_by' => new SalesReportResource($this->whenLoaded('createdBy')),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => number_format($this->quantity * $this->price,2),
            'order_number' => $this->order_number,
            'updated_at' => $this->created_at,
        ];
    }
}
