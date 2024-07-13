<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\SalesReportCollection;
use Carbon\Carbon;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalesReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $startDate = Carbon::parse($request->start_date)->toDateString();
        $endDate = Carbon::parse($request->endDate)->toDateString();

        $order_report = OrderItem::with(['order', 'menu_item', 'customer', 'createdBy'])
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->get();

        return SalesReportCollection::make($order_report);
    }
}
