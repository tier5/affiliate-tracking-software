<?php

namespace App\Http\Controllers;

use App\AgentUrlDetails;
use App\Campaign;
use App\OrderProduct;
use App\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function createProduct(Request $request){
        try{
            $url = parse_url($request->product_url);
            $campaign = Campaign::find($request->campaign_id);
            if($url['host'] == $campaign->url ) {
                $product = new Product();
                $product->campaign_id = $request->campaign_id;
                $product->name = $request->name;
                $product->url = $request->product_url;
                $product->product_price = $request->product_price;
                $product->commission = $request->pricing;
                $product->method = $request->pricingMethod;
                $product->frequency = $request->pricingFrequency;
                $product->plan = $request->pricingPlan;
                $product->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Product Created Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'success' => false,
                    'message' => 'Campaign Url And Landing Page URL is not same'
                ],200);
            }
        } catch (\Exception $exception){
            return response()->json([
                'status' => $exception->getCode(),
                'success' => false,
                'message' => $exception->getMessage()
            ],200);
        }
    }
    public function checkLandingPageUrl(Request $request)
    {
        try{
            $campaign = Campaign::where('key',$request->campaign)->first();
            if($campaign != null){
                $product = Product::where('url',$request->url)->first();
                if($product != null){
                    return response()->json([
                        'success' => true,
                        'message' => 'Landing page found',
                        'data' => $product->id
                    ],200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product Not Found'
                    ],404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Campaign not found'
                ],404);
            }
        } catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ],500);
        }
    }
    public function checkThankYouPage(Request $request)
    {
        try{
            $product = Product::find($request->product_id);
            if($product != null){
                $log = AgentUrlDetails::find($request->log_id);
                if($log != null){
                    $log->type = 2;
                    $log->update();

                    $order = new OrderProduct();
                    $order->log_id = $log->id;
                    $order->product_id = $product->id;
                    $order->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Checkout Successfully'
                    ],200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Log Not Found'
                    ],404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product Not Found'
                ],404);
            }
        } catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ],500);
        }
    }
}
