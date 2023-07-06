<?php

namespace App\Http\Controllers;

use App\Models\Agencia;
use App\Http\Requests\StoreAgenciaRequest;
use App\Http\Requests\UpdateAgenciaRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgenciaController extends Controller{
    public function index(){}
    public function agenciaWebSearch($web){
        $agencia = Agencia::where('web',$web)->first();
        if ($agencia){
            return $agencia;
        }
        return response()->json(['message'=>'Agencia no encontrada'],404);
    }
    public function agenciaProducts(Request $request){
        $search = request()->get('search', '');
        $search = $search == 'null' ? '' : $search;
        $ordenar = request()->get('order', 'id');
        $paginate = request()->get('paginate', 60);
        $products = Product::where('name', 'like', "%$search%")
            ->where('agencia_id', $request->agencia_id)
            ->orderByRaw($ordenar)
            ->paginate($paginate);
        return json_encode(['products' => $products]);
    }
}
