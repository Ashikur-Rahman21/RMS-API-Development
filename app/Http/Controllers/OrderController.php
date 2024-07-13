<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Trait\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Order::orderBy('id', 'desc')->with('customer')->get();
        return response()->json([
            'rows' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'reservation_id' => 'required',
                'status' => 'required',
                'customer_id' => 'required',
                'created_by' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $order = new Order();
            $order->order_number = rand(0, 100);
            $order->reservation_id = $request->reservation_id;
            $order->status = $request->status;
            $order->total = $request->total;
            $order->paid_amount = $request->paid_amount;
            $order->payment_method = $request->payment_method;
            $order->customer_id = $request->customer_id;
            $order->created_by = Auth::user()->id;
            $order->save();
            if (!empty($request->carts)) {
                foreach ($request->carts as $cart) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->menu_item_id = $request->menu_item_id;
                    $orderItem->quantity = $request->quantity;
                    $orderItem->unit_price = $request->unit_price;
                    $orderItem->sub_total = $request->unit_price * $request->quantity;
                    $orderItem->customer_id = $request->customer_id;
                    $orderItem->order_number = $order->order_number;
                    $orderItem->created_by = Auth::user()->id;
                    $orderItem->save();
                }
            }


            if ($request->paid_amount > 0) {
                $payment = new Payment();
                $payment->customer_id = $request->customer_id;
                $payment->order_id = $order->id;
                $payment->order_number = $order->order_number;
                $payment->total_amount = $request->paid_amount;
                $payment->payment_method = $request->payment_method;
                $payment->payment_status = $request->payment_status;
                $payment->created_by = Auth::user()->id;
                $payment->save();

            }
            DB::commit();
            return response()->json(['message' => 'Order created succesfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::find($id);
        $cart = array();
        foreach ($order->orderItems as $row) {

            $data['id'] = $row->id;
            $data['menu_item_id'] = $row->menu_item_id;
            $data['menu_name'] = $row->menu->name;
            $data['quantity'] = $row->quantity;
            $data['unit_price'] = $row->unit_price;
            $data['sub_total'] = $row->sub_total;

            $cart[] = $data;
        }
        return response()->json([
            'order' => $order,
            'cart' => $cart,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'order_number' => 'required',
                'reservation_id' => 'required',
                'status' => 'required',
                'customer_id' => 'required',
                'created_by' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $order = Order::find($id);
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }
            $order = Order::find($id);
            $order->order_number = $request->order_number;
            $order->reservation_id = $request->reservation_id;
            $order->status = $request->status;
            $order->total = $request->total;
            $order->paid_amount = $request->paid_amount;
            $order->payment_method = $request->payment_method;
            $order->customer_id = $request->customer_id;
            $order->created_by = Auth::user()->id;
            $order->save();

            OrderItem::where('order_id', $order->id)->delete();
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->menu_item_id = $request->menu_item_id;
            $orderItem->quantity = $request->quantity;
            $orderItem->unit_price = $request->unit_price;
            $orderItem->sub_total = $request->unit_price * $request->quantity;
            $orderItem->customer_id = $request->customer_id;
            $orderItem->order_number = $order->order_number;
            $orderItem->created_by = Auth::user()->id;
            $orderItem->save();

            Payment::where('order_id', $order->id)->delete();
            if ($request->paid_amount > 0) {
                $payment = new Payment();
                $payment->customer_id = $request->customer_id;
                $payment->order_id = $order->id;
                $payment->order_number = $order->order_number;
                $payment->total_amount = $request->paid_amount;
                $payment->payment_method = $request->payment_method;
                $payment->payment_status = $request->payment_status;
                $payment->created_by = Auth::user()->id;
                $payment->save();
            }
            DB::commit();
            return response()->json(['message' => 'Order update successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }

    public function invoiceGenerate($id)
    {
        try {
            $order = Order::findOrFail($id);
            $orderItem = OrderItem::where('order_id', $order->id)->get();
            return response()->json([
                'order' => $order,
                'orderItem' => $orderItem
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error(
                message: "Order not found.",
                statusCode: Response::HTTP_NOT_FOUND
            );
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);
        OrderItem::where('order_id', $order->id)->delete();
        Payment::where('order_id', $order->id)->delete();
        $order->delete();
        return response()->json(['message' => 'Order delete succesfully']);
    }
}
