<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function saleProduct(Request $request)
    {
        return Sale::selectRaw('products.name as product, sum(details.quantity) as quantity')
            ->join('details', 'details.sale_id', '=', 'sales.id')
            ->join('products', 'products.id', '=', 'details.product_id')
            ->whereBetween('sales.date', [$request->dateIni, $request->dateFin])
            ->where('sales.agencia_id', $request->user()->agencia_id)
            ->where('sales.canceled', 'No')
            ->groupBy('products.name')
            ->get();
    }
}
