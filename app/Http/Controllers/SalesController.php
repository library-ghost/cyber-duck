<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleCalculatePriceRequest;
use App\Http\Requests\SaleRecordRequest;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SalesController extends Controller
{
    /**
     * Show the page to record sales
     */
    public function index(): View
    {
        return view('coffee_sales', [
            'sales' => Sale::getSalesRecords(),
            'products' => Product::select('id', 'name')->get()->toArray(),
        ]);
    }

    /**
     * Store a sale record
     */
    public function store(SaleRecordRequest $request): JsonResponse
    {
        $sale = Sale::createWithAttributes([
            'quantity' => $request->get('quantity'),
            'unit_cost' => $request->get('unit_cost'),
            'selling_price' => $request->get('selling_price'),
            'product_id' => $request->get('product_id'),
        ]);

        return response()->json([
            'sale' => $sale->asTableData()
        ]);
    }

    /**
     * Calculate the selling price
     */
    public function calculate(SaleCalculatePriceRequest $request): JsonResponse
    {
        $value = Sale::calculateSellingPrice(
            $request->get('quantity'),
            $request->get('unit_cost'),
        );

        return response()->json(['selling_price' => $value]);
    }

}
