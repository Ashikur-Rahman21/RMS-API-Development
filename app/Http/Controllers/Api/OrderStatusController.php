<?php

namespace App\Http\Controllers\Api;

use App\Enums\Status;
use App\Models\Order;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Api\OrderStatusResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderStatusController extends Controller
{
    use ApiResponse;
    public function getOrderStatus(string $id)
    {   
        try {
            $order_status = Order::findOrFail($id);
            return OrderStatusResource::make($order_status);
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: 'Status cannot be found.', statusCode: Response::HTTP_NOT_FOUND);
        } 
    }

    public function updateStatus(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'status' => ['required', Rule::enum(Status::class)]
            ]);

            $order_status = Order::findOrFail($id);
            $order_status->update($validated);
            return OrderStatusResource::make($order_status);
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: 'Status cannot be found.', statusCode: Response::HTTP_NOT_FOUND);
        } 
    }
}
