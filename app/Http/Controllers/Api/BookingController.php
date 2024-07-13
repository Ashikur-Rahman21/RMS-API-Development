<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReservationRequest;
use App\Http\Resources\Api\ReservationResource;
use App\Models\Reservation;
use App\Models\Table;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    use ApiResponse;

    public function availableTable(Request $request)
    {
        $request->validate([
            'num_guest' => 'required|integer|min:1',
            'date' => 'required|date|after:yesterday',
            'start_time' => 'required',
        ]);

        $date = $request->input('date');
        $time = $request->input('start_time');
        $numGuest = $request->input('num_guest');

        $reservation = Reservation::where('date', $date)
            ->where('start_time', $time)
            ->pluck('table_id');

        $availableTable = Table::whereNotIn('id', $reservation)
            ->where('status', 'available')
            ->where('seats', ">=", $numGuest)
            ->get();

        return $this->success(message: 'Available table list.',
        data: $availableTable, statusCode: Response::HTTP_OK);
    }

    public function store(StoreReservationRequest $request)
    {
        $userId = $request->input('user_id');
        $status = 'booked';

        if (!$userId) {
            $userId = Auth::user()->id;
        }

        $data = [
            'user_id' => $userId,
            'status' => $status,
            'table_id' => $request->input('table_id'),
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'num_guest' => $request->input('num_guest')
        ];

        $result = Reservation::create($data);
        $reservation = ReservationResource::make($result);

        return $this->success(
            message: 'Your reservation successfully done.',
            data: $reservation,
            statusCode: Response::HTTP_OK
        );
    }
}
