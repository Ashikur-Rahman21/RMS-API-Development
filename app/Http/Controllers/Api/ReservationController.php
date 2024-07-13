<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReservationRequest;
use App\Http\Requests\Api\UpdateReservationRequest;
use App\Http\Resources\Api\ReservationCollection;
use App\Http\Resources\Api\ReservationResource;
use App\Models\Reservation;
use App\Trait\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::all();
        return ReservationCollection::make($reservations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $reservation = Reservation::create($request->validated());
        $reservationCollection = ReservationResource::make($reservation);

        return $this->success(message: "Reservation Created Successfully.", data: $reservationCollection,
            statusCode: Response::HTTP_CREATED);
    }

    public function show(string $reservationId)
    {
        try {
            return ReservationResource::make(Reservation::findOrFail($reservationId));
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: 'Reservation cannot be found.', statusCode: Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, string $reservationId)
    {
        try {
            $reservation = Reservation::findOrFail($reservationId);

            $reservation->update($request->validated());

            $reservationResource = new ReservationResource($reservation);
            return $this->success(message: "Reservation Updated Successfully.", data: $reservationResource,
                statusCode: Response::HTTP_CREATED);
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: 'Reservation cannot be found.', statusCode: Response::HTTP_NOT_FOUND);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $reservationId)
    {
        try {
            $reservation = Reservation::findOrFail($reservationId);
            $reservation->delete();
            return $this->success(message: 'Reservation successfully deleted.', statusCode: Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: 'Reservation cannot be found.', statusCode: Response::HTTP_NOT_FOUND);
        }
    }
}
