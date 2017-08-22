<?php

namespace App\Http\Controllers;

use App\AgentUrlDetails;
use App\Campaign;
use App\OrderProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use \Exception;
use \Log;

class ProductController extends Controller
{
    public function chooseProduct(Request $request)
    {
        try{
            $campaign = Campaign::findOrFail($request->campaign_id);
            $campaign->product_type=$request->product_type;
            $campaign->update();
            
            $response = [
                'status' => true,
                'message' => 'Your Product Preference Has Been Added Successfully!',
            ];
            $responseCode = 201;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => "No campaign has been found.",
                'error_info' => preg_replace('/(\\[App\\\\)|(\\])/', '', $modelNotFoundException->getMessage())
            ];
            $responseCode = 404;
        } catch(Exception $exception){
            Log::info($exception->getMessage());

            $response = [
                'status' => false,
                'error' => "Internal server error.",
                'error_info' => $exception->getMessage(),
            ];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function createProduct(Request $request)
    {
        try {
            $url = parse_url($request->url);
            $campaign = Campaign::findOrFail($request->campaign_id);
            if ($url['host'] == $campaign->url) {
                $product = new Product();
                $product->campaign_id = $request->campaign_id;
                $product->name = $request->name;
                $product->url = $request->url;
                $product->upgrade_url = $request->upgrade_url;
//                $product->downgrade_url = $request->downgrade_url;
                $product->product_price = $request->product_price;
                $product->commission = $request->commission;
                $product->method = $request->commissionMethod;
                $product->frequency = $request->commissionFrequency;
                $product->plan = $request->commissionPlan;
                $product->save();

                $response = [
                    'status' => true,
                    'message' => 'Product has been created successfully.',
                    'product' => $product
                ];
                $responseCode = 201;
            } else {
                $response = [
                    'status' => false,
                    'error' => 'Campaign URL & product page URL should be same.'
                ];
                $responseCode = 400;
            }
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => "No campaign has been found.",
                'error_info' => preg_replace('/(\\[App\\\\)|(\\])/', '', $modelNotFoundException->getMessage())
            ];
            $responseCode = 404;
        } catch (QueryException $queryException) {
            if (preg_match('/(products_url_unique)/', $queryException->getMessage())) {
                $response = [
                    'status' => false,
                    'error' => "URL should be unique.",
                    'error_info' => $queryException->getMessage()
                ];
                $responseCode = 409;
            } else {
                $response = [
                    'status' => false,
                    'error' => "Internal server error.",
                    'error_info' => $queryException->getMessage()
                ];
                $responseCode = 500;
            }
        } catch (Exception $exception){
            Log::info($exception->getMessage());

            $response = [
                'status' => false,
                'error' => 'Internal server error.',
                'error_info' => $exception->getMessage()
            ];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function checkLandingPageUrl(Request $request)
    {
        try {
            $campaign = Campaign::where('key', $request->campaign)->firstOrFail();
            $product = Product::where('url', $request->url)
                    ->where('campaign_id', $campaign->id)
                    ->firstOrFail();
            if($request->currentUrl != 0){
                if($product->upgrade_url == $request->currentUrl){
                    $response = [
                        'status' => true,
                        'message' => 'Landing page found',
                        'data' => $product->id
                    ];
                    $responseCode = 200;
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'Click on checkout Page',
                    ];
                    $responseCode = 406 ;
                }
            } else {
                $response = [
                    'status' => true,
                    'message' => 'Landing page found',
                    'data' => $product->id
                ];
                $responseCode = 200;
            }
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => preg_match('/(Campaign)/', $modelNotFoundException->getMessage())
                    ? "No campaign has been found." : "No product has been found.",
                'error_info' => preg_replace('/(\\[App\\\\)|(\\])/', '', $modelNotFoundException->getMessage())
            ];
            $responseCode = 404;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());

            $response = [
                'status' => false,
                'message' => $exception->getMessage()
            ];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function deleteProduct(Request $request)
    {
        try {
            $product = Product::findOrFail($request->input('id'));
            $product->delete();

            $response = [];
            $responseCode = 204;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => "No matching product has been found to delete.",
                'error_info' => preg_replace('/(\\[App\\\\)|(\\])/', '', $modelNotFoundException->getMessage())
            ];
            $responseCode = 404;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());

            $response = [
                'status' => false,
                'error' => "Internal server error.",
                'error_info' => $exception->getMessage()
            ];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function getProduct(Request $request)
    {
        try {
            $product = Product::findOrFail($request->input('id'));

            $response = [
                'status' => true,
                'message' => "Product found successfully.",
                'product' => $product
            ];
            $responseCode = 200;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => "No matching product has been found to fetch.",
                'error_info' => preg_replace('/(\\[App\\\\)|(\\])/', '', $modelNotFoundException->getMessage())
            ];
            $responseCode = 404;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());

            $response = [
                'status' => false,
                'error' => "Internal server error.",
                'error_info' => $exception->getMessage()
            ];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function checkThankYouPage(Request $request)
    {
        try{
            $product = Product::findOrFail($request->product_id);

            $log = AgentUrlDetails::findOrFail($request->log_id);
            $log->type = 2;
            $log->update();

            $order = new OrderProduct();
            $order->log_id = $log->id;
            $order->product_id = $product->id;
            $order->save();

            $response = [
                'status' => true,
                'message' => 'Checkout Successfully'
            ];
            $responseCode = 200;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => preg_match('/(Product)/', $modelNotFoundException->getMessage())
                    ? "No product has been found." : "No log has been found.",
                'error_info' => preg_replace('/(\\[App\\\\)|(\\])/', '', $modelNotFoundException->getMessage())
            ];
            $responseCode = 404;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());

            $response = [
                'status' => false,
                'error' => "Internal server error.",
                'error_info' => $exception->getMessage()
            ];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function editProduct(Request $request)
    {
        try {
            $url = parse_url($request->url);
            $campaign = Campaign::findOrFail($request->campaign_id);

            if ($url['host'] == $campaign->url) {
                $product = Product::findOrFail($request->input('id'));
                $product->name = $request->name;
                $product->url = $request->url;
                $product->upgrade_url = $request->upgrade_url;
                $product->downgrade_url = $request->downgrade_url;
                $product->product_price = $request->product_price;
                $product->commission = $request->commission;
                $product->method = $request->commissionMethod;
                $product->frequency = $request->commissionFrequency;
                $product->plan = $request->commissionPlan;
                $product->update();

                $response = [
                    'status' => true,
                    'message' => "Product has been updated successfully.",
                    'product' => $product
                ];
                $responseCode = 201;
            } else {
                $response = [
                    'status' => false,
                    'error' => 'Campaign URL & prduct page URL should be same.'
                ];
                $responseCode = 400;
            }
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => "No product has been found.",
                'error_info' => preg_replace('/(\\[App\\\\)|(\\])/', '', $modelNotFoundException->getMessage())
            ];
            $responseCode = 404;
        } catch (QueryException $queryException) {
            if (preg_match('/(products_url_unique)/', $queryException->getMessage())) {
                $response = [
                    'status' => false,
                    'error' => "URL should be unique.",
                    'error_info' => $queryException->getMessage()
                ];
                $responseCode = 409;
            } else {
                $response = [
                    'status' => false,
                    'error' => "Internal server error.",
                    'error_info' => $queryException->getMessage()
                ];
                $responseCode = 500;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());

            $response = [
                'status' => false,
                'error' => "Internal server error.",
                'error_info' => $exception->getMessage()
            ];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
    public function checkOrderUrl(Request $request)
    {
        try{
            $campaign_url = Campaign::where('key',$request->campaign_id)
                ->firstOrFail();

            $response = [
                'status' => true,
                'message' => "Campaign Found",
                'data' => $campaign_url->campaign_url
            ];
            $responseCode = 200;
        }  catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => "No Campaign has been found.",
                'error_info' => preg_replace('/(\\[App\\\\)|(\\])/', '', $modelNotFoundException->getMessage())
            ];
            $responseCode = 404;
        }  catch (\Exception $exception){
            $response = [
                'status' => false,
                'error' => "Internal server error.",
                'error_info' => $exception->getMessage()
            ];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
}
