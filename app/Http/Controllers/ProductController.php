<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function createProduct(Request $request){
        try{
            $product = new Product();
            $product->campaign_id = $request->campaign_id;
            $product->name = $request->name;
            $product->commission = $request->pricing;
            $product->method = $request->pricingMethod;
            $product->frequency = $request->pricingFrequency;
            $product->plan = $request->pricingPlan;
            $product->save();
            return response()->json([
                'success' => true,
                'message' => 'Product Created Successfully'
            ],200);
        } catch (\Exception $exception){
            return response()->json([
                'status' => $exception->getCode(),
                'success' => false,
                'message' => $exception->getMessage()
            ],200);
        }
    }
}
