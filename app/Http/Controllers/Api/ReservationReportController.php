<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ReservationCollection;

class ReservationReportController extends Controller
{
    public function reservationReport(Request $request)
    {
        $reservations = Reservation::with('user', 'table')->get();
        return ReservationCollection::make($reservations);
    }
}
