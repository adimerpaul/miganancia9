<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller{
    public function index(){
        $search = request()->get('search', '');
        $search = $search == 'null' ? '' : $search;
        $ordenar = request()->get('order', 'id');
//        $category_id = request()->get('category', 'id');
//        $agencia_id = request()->get('agencia', 'id');
        $paginate = request()->get('paginate', 30);
//        if ($category_id == 0 && $agencia_id == 0){
        $products = Product::where('name', 'like', "%$search%")
            ->orderByRaw($ordenar)
            ->paginate($paginate);
            $costoRow=Product::select(DB::raw('sum(cost*amount)'))
                ->where('name', 'like', "%$search%")
                ->first();
            $costoTotal=$costoRow["sum(cost*amount)"]==null?0:$costoRow["sum(cost*amount)"];
//        }else{
//            if ($category_id == 0 && $agencia_id != 0){
//                $products = Product::where('name', 'like', "%$search%")
//                    ->where('agencia_id', $agencia_id)
//                    ->orderByRaw($ordenar)
//                    ->paginate($paginate);
//                $costoRow=Product::select(DB::raw('sum(costo*cantidad)'))
//                    ->where('name', 'like', "%$search%")
//                    ->where('agencia_id', $agencia_id)
//                    ->first();
//                $costoTotal=$costoRow["sum(costo*cantidad)"]==null?0:$costoRow["sum(costo*cantidad)"];
//            }else if ($category_id != 0 && $agencia_id == 0){
//                $products = Product::where('name', 'like', "%$search%")
//                    ->where('category_id', $category_id)
//                    ->orderByRaw($ordenar)
//                    ->paginate($paginate);
//                $costoRow=Product::select(DB::raw('sum(costo*cantidad)'))
//                    ->where('name', 'like', "%$search%")
//                    ->where('category_id', $category_id)
//                    ->first();
//                $costoTotal=$costoRow["sum(costo*cantidad)"]==null?0:$costoRow["sum(costo*cantidad)"];
//            }else if ($category_id != 0 && $agencia_id != 0){
//                $products = Product::where('name', 'like', "%$search%")
//                    ->where('category_id', $category_id)
//                    ->where('agencia_id', $agencia_id)
//                    ->orderByRaw($ordenar)
//                    ->paginate($paginate);
//                $costoRow=Product::select(DB::raw('sum(costo*cantidad)'))
//                    ->where('name', 'like', "%$search%")
//                    ->where('category_id', $category_id)
//                    ->where('agencia_id', $agencia_id)
//                    ->first();
//                $costoTotal=$costoRow["sum(costo*cantidad)"]==null?0:$costoRow["sum(costo*cantidad)"];
//            }
//        }
        return json_encode(['products' => $products, 'costoTotal' => $costoTotal]);
    }
    public function store(StoreProductRequest $request){
//        if ($request->category_id == 0) $request->merge(['category_id' => null]);
        if ($request->agencia_id == 0) $request->merge(['agencia_id' => $request->user()->agencia_id]);
        return Product::create($request->all());
    }
    public function show(Product $product){ return $product; }
    public function update(UpdateProductRequest $request, Product $product){
        return $product->update($request->all());
    }
    public function destroy(Product $product){ return $product->delete(); }
}
