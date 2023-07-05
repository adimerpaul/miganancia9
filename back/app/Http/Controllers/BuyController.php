<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use App\Http\Requests\StoreBuyRequest;
use App\Http\Requests\UpdateBuyRequest;
use App\Models\Product;

class BuyController extends Controller{
    public function index(){
        $buys = Buy::with('client', 'product', 'user')
            ->where('agencia_id', request()->user()->agencia_id)
            ->where('dateExpiration', '>=', date('Y-m-d'))
            ->orderBy('dateExpiration', 'asc')
            ->get();
        foreach ($buys as $buy){
            $buy->days = $this->calculateDias($buy->dateExpiration);
        }
        return response()->json($buys, 200);
    }
    public function calculateDias($dateExpiration){
        $date = date('Y-m-d');
        $dias = (strtotime($dateExpiration)-strtotime($date))/86400;
        $dias = $dias<0?0:$dias;
        $dias = floor($dias);
        return $dias;
    }
    public function store(StoreBuyRequest $request){
        $textProducto = '';
        if ($request->product_id != null && $request->product_id != ''){
            $product = Product::find($request->product_id);
            $textProducto = 'Compra de '.$product->name;
        }
        if ($request->description == null || $request->description == ''){
            $request->merge(['description' => $textProducto]);
        }else{
            $request->merge(['description' => $request->description.' '.$textProducto]);
        }
        $request->merge(['agencia_id' => $request->user()->agencia_id]);
        $request->merge(['user_id' => $request->user()->id]);
        $request->merge(['quantity' => $request->amount]);
        $buy = Buy::create($request->all());
        if ($request->product_id != null && $request->product_id != ''){
            $product = Product::find($request->product_id);
            $product->amount = $product->amount + $request->amount;
            $product->save();
        }
        return response()->json($buy, 201);
    }
    public function show(Buy $buy){
        return $buy;
    }
    public function update(UpdateBuyRequest $request, Buy $buy){
        $buy->update($request->all());
        return response()->json($buy, 200);
    }
    public function destroy(Buy $buy){
        $buy->delete();
        return response()->json(null, 204);
    }
}
