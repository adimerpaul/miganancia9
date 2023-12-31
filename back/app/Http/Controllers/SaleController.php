<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalesRequest;
use App\Models\Client;
use App\Models\Detail;
use App\Models\Product;
use App\Models\Sale;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Sales;
use Illuminate\Http\Request;

class SaleController extends Controller{
    public function index(Request $request){
        $dateInit = $request->get('dateIni');
        $dateEnd = $request->get('dateFin');
        $sales = Sale::whereBetween('date', [$dateInit, $dateEnd])
            ->where('agencia_id', $request->user()->agencia_id)
            ->with(['details','client','user'])
            ->orderBy('id','desc')->get();
        return $sales;
    }
    public function salesGasto(StoreSaleRequest $request){
        if ($request->concepto == ''){
            $numeroGasto = Sale::where('type','Egreso')->count();
            $request->concepto = 'Gasto '.($numeroGasto+1);
        }
        if ($request->client_id == 0){
            $request->merge(['client_id'=>null]);
            $request->merge(['client'=>['name'=>'SN']]);
        }else{
            $client = Client::find($request->client_id);
            $request->merge(['client'=>['name'=>$client->name]]);
        }
        $sales = new Sale();
        $sales->date = date('Y-m-d');
        $sales->time = date('H:i:s');
        $sales->dateTime = date('Y-m-d H:i:s');
        $sales->total = $request->montoTotal;
        $sales->description = $request->concepto;
        $sales->type = 'Egreso';
        $sales->client_id = $request->client_id==0?null:$request->client_id;
        $sales->clientName = strtoupper($request->client['name']);
        $sales->user_id = $request->user()->id;
        $sales->agencia_id = $request->user()->agencia_id;

        $sales->save();

        $detail = new Detail();
        $detail->sale_id = $sales->id;
        $detail->user_id = $request->user()->id;
        $detail->product_id = null;
        $detail->price = $request->montoTotal;
        $detail->quantity = 1;
        $detail->total = $request->montoTotal;
        $detail->productName = $request->concepto;

        $detail->save();
        return $sales->with(['details','client','user'])->find($sales->id);

    }
    public function update(UpdateSaleRequest $request, $id){
        $sale = Sale::find($id);
        $sale->update($request->all());
        return $sale;
    }
    public function store(StoreSaleRequest $request){

        $client=$this->insertUpdateClient($request);
        $sales = new Sale();
        $sales->client_id = $client->id;
        $sales->user_id = $request->user()->id;
        $sales->agencia_id = $request->user()->agencia_id;
        $sales->total = $request->montoTotal;
        $sales->clientName = strtoupper($request->client['name']);
//        $sales->numeroFactura = 0;
        $sales->date = date('Y-m-d');
        $sales->time = date('H:i:s');
        $sales->datetime = date('Y-m-d H:i:s');
        $sales->type = 'Ingreso';
        $sales->reservation = $request->reservation;
        $sales->paraLlevar = $request->paraLlevar;
        $sales->delivery = $request->delivery;
        $sales->deliveryAddress = $request->deliveryAddress;
        $sales->deliveryCost = $request->deliveryCost;

//        $sales->usuario = $request->user()->name;
//        $sales->venta = 'R';
//        $sales->tipoVenta = 'Ingreso';
//        $sales->metodoPago = $request->metodoPago;
//        $sales->aporte = $request->aporte;

        $sales->save();
        $concepto = "";
        foreach ($request->products as $product){
            $productSale= Product::find($product['id']);
            $productSale->amount = $productSale->amount - $product['cantidadPedida'];
            $productSale->save();

            $detail = new Detail();
            $detail->sale_id = $sales->id;
            $detail->user_id = $request->user()->id;
            $detail->product_id = $product['id'];
            $detail->price = $product['precioVenta'];
            $detail->quantity = $product['cantidadPedida'];
            $detail->total = $product['subTotal'];
            $detail->productName = $product['name'];
            $detail->save();
            $concepto .= $product['cantidadPedida'].$product['name'].',';
        }
        if ($request->deliveryCost > 0){
            $detail = new Detail();
            $detail->sale_id = $sales->id;
            $detail->user_id = $request->user()->id;
            $detail->product_id = null;
            $detail->price = $request->deliveryCost;
            $detail->quantity = 1;
            $detail->total = $request->deliveryCost;
            $detail->productName = 'COSTO DE ENVIO';
            $detail->save();
        }
        $sales->description = substr($concepto,0,-1);
        $sales->save();

        return Sale::with(['details','client'])->find($sales->id);

    }
    public function insertUpdateClient(Request $request)
    {
        if ($request->client['nit'] == 0) {
            $client = Client::where('nit', $request->client['nit'])->first();
            return $client;
        }
        if (Client::where('nit', $request->client['nit'])->count()) {
            $client = Client::where('nit', $request->client['nit'])->first();
            $client->name = strtoupper($request->client['name']);
//            $client->codigoTipoDocumentoIdentidad = $request->client['codigoTipoDocumentoIdentidad'];
            $client->phone = $request->client['phone'];
            $client->save();
            return $client;
//            return "actualizado";
        } else {
            $client = new Client();
            $client->name = strtoupper($request->client['name']);
//            $client->codigoTipoDocumentoIdentidad = $request->client['codigoTipoDocumentoIdentidad'];
            $client->nit = $request->client['nit'];
//            $client->complemento = strtoupper($request->client['complemento']);
            $client->phone = $request->client['phone'];
            $client->save();
            return $client;
//            return "nuevo";
        }
    }
}
