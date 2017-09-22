<?php

namespace App\Http\Controllers;

use App\Affiliate;
use App\AgentUrlDetails;
use App\Campaign;
use App\Jobs\sendPurchaseEmail;
use App\Jobs\sendPurchaseUpdateEmail;
use App\OrderProduct;
use App\Product;
use App\SalesDetail;
use App\User;
use Cartalyst\Stripe\Stripe;
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
                $product->upgrade_commission = $request->upgradeCommission;
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
    public function checkLandingPageUrlV2(Request $request)
    {
        try {
            $campaign = Campaign::where('key', $request->campaign)->firstOrFail();
            $product = Product::where('url', $request->previous_url)
                ->where('campaign_id', $campaign->id)
                ->firstOrFail();
            if($product->upgrade_url == $request->currentUrl){

                $log = AgentUrlDetails::findOrFail($request->log_id);
                $log->type = 2;
                $log->update();

                $affiliate = Affiliate::find($log->affiliate_id);
                $user = User::find($affiliate->user_id);
                $user_name = $user->name;
                $user_email = $user->email;
                $product_price = $product->product_price;

                if ($product->method == 1) {
                    $product_commission = $product->product_price * ($product->commission / 100);
                } else {
                    $product_commission = $product->commission;
                }

                if(isset($request->order_id) && $request->order_id > 0){
                    $order = OrderProduct::find($request->order_id);
                    $oldProductObj = Product::find($order->product_id);
                    $order->product_id = $product->id;
                    $order->update();

                    $oldProduct = $oldProductObj->name;

                    $job = (new sendPurchaseUpdateEmail($oldProduct,$user_name,$user_email,$product->name,$product_price,$product_commission,$campaign->name));
                    $this->dispatch($job);
                } else {
                    $order = new OrderProduct();
                    $order->log_id = $log->id;
                    $order->product_id = $product->id;
                    $order->email = $log->email;
                    $order->save();

                    $job = (new sendPurchaseEmail($user_name,$user_email,$product->name,$product_price,$product_commission,$campaign->name));
                    $this->dispatch($job);
                }

                $salesData = new SalesDetail();
                $salesData->sales_id = $order->id;
                $salesData->product_amount = $product->product_price;
                $salesData->step_payment_amount = $product->product_price;
                $salesData->type = 1;
                $salesData->status = 1;
                $salesData->commission = $product_commission;
                $salesData->save();

                Log::info('Product sold from script');

                $response = [
                    'status' => true,
                    'message' => 'Product Sold',
                    'data' => $order->id
                ];
                $responseCode = 200;
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Click on no thanks',
                ];
                $responseCode = 406 ;
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
    public function checkProduct(Request $request)
    {
        try {
            $campaign = Campaign::where('key', $request->campaign)->firstOrFail();
            if($campaign->test_pk != '' && $campaign->test_sk != '' && $campaign->live_pk != '' && $campaign->live_sk != '') {
                $response = [
                    'status' => false,
                    'message' => 'This is a stripe campaign',
                ];
                $responseCode = 400 ;
            } else {
                $product = Product::where('url', $request->previous_url)
                    ->where('campaign_id', $campaign->id)
                    ->firstOrFail();
                if($product){
                    $response = [
                        'status' => true,
                        'message' => 'Landing page found'
                    ];
                    $responseCode = 200;
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'Click on checkout Page',
                    ];
                    $responseCode = 406 ;
                }
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
    public function checkOrderUrlV2(Request $request)
    {
        try {
            $campaign = Campaign::where('key', $request->campaign)->firstOrFail();
            if($campaign->campaign_url == $request->current_url){
                $response = [
                    'status' => true,
                    'message' => 'Landing page found'
                ];
                $responseCode = 200;
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Not a Order Page',
                ];
                $responseCode = 406 ;
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
    public function fetchStripePlans(Request $request)
    {
        try{
            $campaign = Campaign::findOrFail($request->campaign);
            if($campaign->test_sk != '' && $campaign->test_pk != '' && $campaign->live_sk != '' && $campaign->live_pk != ''){
                if($campaign->stripe_mode == 1){
                    $key = $campaign->test_sk;
                } else {
                    $key = $campaign->live_sk;
                }
                $stripe = Stripe::make($key);
                $plans = $stripe->plans()->all([
                    'limit' => '100'
                ]);
                if(count($plans['data']) > 0){
                    $html = view('admin.stripeProduct',['campaign' => $campaign,'products' => $plans['data']]);
                } else {
                    $html = '<h3>No Product Found </h3>';
                }
            } else {
                $html = '<h2>Please integrate stripe to this campaign</h2>';
            }
        } catch (\Exception $exception){
            $html = '<h3>'.$exception->getMessage().'</h3>';
        } catch (ModelNotFoundException $modelNotFoundException) {
            $html = '<h3>'.$modelNotFoundException->getMessage().'</h3>';
        }
        return $html;
    }
}
