<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;
class ProductController extends Controller{
    public function index(Request $request){
        $search = request()->get('search', '');
        $search = $search == 'null' ? '' : $search;
        $ordenar = request()->get('order', 'id');
//        $category_id = request()->get('category', 'id');
//        $agencia_id = request()->get('agencia', 'id');
        $paginate = request()->get('paginate', 30);
//        if ($category_id == 0 && $agencia_id == 0){
        $products = Product::where('name', 'like', "%$search%")
            ->where('agencia_id', $request->user()->agencia_id)
            ->orderByRaw($ordenar)
            ->paginate($paginate);
            $costoRow=Product::select(DB::raw('sum(cost*amount)'))
                ->where('agencia_id', $request->user()->agencia_id)
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
    public function productAll(Request $request){
        return Product::where('agencia_id', $request->user()->agencia_id)->get();
    }
    public function productAllBase64(Request $request){
        $products= Product::where('agencia_id', $request->user()->agencia_id)->get();
        foreach ($products as $product){
            $product->base64=$this->getBase64($product->image);
        }
        return $products;
    }
    public function getBase64($image){
        if (str_contains($image, 'http')){
            error_log($image);
            $url = $image;
            $image = file_get_contents($url);
            if ($image !== false){
                return base64_encode($image);
            }
//            $img = Image::make($image)->resize(320, 240)->insert('public/watermark.png');

//            Storage::disk('local')->put('arjunphp_laravel.png', file_get_contents('https://arjunphp.com/wp-content/uploads/2015/06/arjunphp_laravel.png'));

//            copy($image, '/images/file.png');
//            return base64_encode(file_get_contents(public_path('images/file.png')));
        }else{
            return 'data:image/jpg;base64,'.base64_encode(file_get_contents(public_path('images/'.$image)));
        }
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
