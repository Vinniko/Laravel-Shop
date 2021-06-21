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
       $options = [];
       if($request->has('filters')) $options = $request->input('filters');
       $keys = array_keys($options);
       $conditions = [];
       foreach($keys as $key){
            array_push($conditions, ['options', 'like', '%"'.$key.'": "'.$options[$key].'"%']);
       }
       $products = Product::where($conditions)->paginate(40);
       return response()->json($products);
   }
}
