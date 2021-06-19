<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Log;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   public function index(Request $request){
       $products = Product::paginate(40);
       return response()->json($products);
   }

   public function filter(Request $request){
       $title = "";
       $min_price = 0;
       $max_price = 60000;
       $min_qty = 0;
       $max_qty = 60000;
       if($request->has('min_price')) $min_price = $request->input('min_price');
       if($request->has('max_price')) $max_price = $request->input('max_price');
       if($request->has('min_qty'))  $min_qty =$request->input('min_qty');
       if($request->has('max_qty')) $max_qty =$request->input('max_qty');
       $products = Product::where('price', '>', $min_price)
            ->where('price', '<', $max_price)
            ->where('qty', '>', $min_qty)
            ->where('qty', '<', $max_qty)
            ->paginate(40);
        $result = [];
        if($request->has('title')){
            $title = $request->input('title');
            foreach ($products as $product)
            {
                if(mb_strpos($product->title, $title) !== false){
                    array_push($result, $product);
                }
            }
        } 
        else{
            $result = $products;
        }
        return response()->json($result);
   }
}
