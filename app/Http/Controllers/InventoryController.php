<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventory = Inventory::orderBy('id', 'asc')->get();
        return response()->json([
            'status' => 200,
            'rows' => $inventory
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function alertInventoryList()
    {
        $inventory = Inventory::orderBy('id', 'asc')
                    ->where('threshold', '<' , 50)->get();
        return response()->json([
            'status' => 200,
            'rows' => $inventory
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function alertInventory()
    {
        $inventory = Inventory::where('threshold', '<', 50)
                    ->take(20)->get();
        
        return response()->json([
            'status' => 200,
            'rows' => $inventory
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function inventoryReport(Request $request)
    {
        // Fetch inventory data
        $inventory = Inventory::orderBy('id','asc')->get(); // You can add more complex queries and filters as needed

        // Decide on the report format
        $format = $request->query('format', 'json'); // default to JSON if no format specified

        switch ($format) {
            case 'pdf':
                return $this->generatePdfReport($inventory);
            case 'csv':
                return $this->generateCsvReport($inventory);
            default:
                return response()->json([
                    'status' => 200,
                    'data' => $inventory
                ]);
        }
    }

    private function generatePdfReport($inventory)
    {
        $pdf = PDF::loadView('inventory_report', compact('inventory'));
        return $pdf->download('inventory_report.pdf');
    }

    private function generateCsvReport($inventory)
    {
        $csvData = $inventory->map(function ($item) {
            return $item->toArray();
        });

        // Using Laravel Excel to export CSV
        return Excel::download(new InventoryExport($csvData), 'inventory_report.csv');
    }
}
