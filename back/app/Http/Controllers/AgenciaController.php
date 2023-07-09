<?php

namespace App\Http\Controllers;

use App\Models\Agencia;
use App\Http\Requests\StoreAgenciaRequest;
use App\Http\Requests\UpdateAgenciaRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgenciaController extends Controller{
    public function index(){
        return Agencia::all();
    }
    public function store(StoreAgenciaRequest $request){
        $agencia = Agencia::create($request->all());
        $user = new User();
        $user->name = 'Administador';
        $user->nickname = $agencia->web.'Admin';
        $user->password = bcrypt($agencia->web.'123A');
        $user->type = 'admin';
        $user->agencia_id = $agencia->id;
        $user->save();
        $user = new User();
        $user->name = 'Vendedor';
        $user->nickname = $agencia->web.'Vendedor';
        $user->password = bcrypt($agencia->web.'123');
        $user->type = 'user';
        $user->agencia_id = $agencia->id;
        $user->save();
        return response()->json($agencia, 201);
    }
    public function update(UpdateAgenciaRequest $request, Agencia $agencia){
        $agencia->update($request->all());
        $user = User::where('agencia_id',$agencia->id)->where('type','admin')->first();
        $user->nickname = $agencia->web.'Admin';
        $user->password = bcrypt($agencia->web.'123A');
        $user->save();
        $user = User::where('agencia_id',$agencia->id)->where('type','user')->first();
        $user->nickname = $agencia->web.'Vendedor';
        $user->password = bcrypt($agencia->web.'123');
        $user->save();
        return response()->json($agencia, 200);
    }
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
