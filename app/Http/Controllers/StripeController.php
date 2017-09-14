<?php

namespace App\Http\Controllers;

use App\Affiliate;
use App\AgentUrlDetails;
use App\Campaign;
use App\OrderProduct;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function refreshController(Request $request)
    {
        try {
            $sale = OrderProduct::findOrFail($request->id);
            $log = AgentUrlDetails::findOrFail($sale->log_id);
            $affiliate = Affiliate::findOrFail($log->affiliate_id);
            $campaign = Campaign::findOrFail($affiliate->campaign_id);
            if($campaign->test_sk != '' && $campaign->test_pk != '' && $campaign->live_pk != '' && $campaign->live_sk){
                if($campaign->stripe_mode == 1){
                    $key = $campaign->test_sk;
                } else {
                    $key = $campaign->live_sk;
                }
                $stripe = Stripe::make($key);
                $customers = $stripe->customers()->all();
                foreach ($customers['data'] as $customer){
                    if($customer['email'] == $sale->email){
                        $sale->customer_id = $customer['id'];
                        $sale->stripe_status = $customer['subscriptions']['data'][0]['status'];
                        $sale->update();
                        return response()->json([
                            'success' => true,
                            'data' => $customer['subscriptions']['data'][0]['status']
                        ],200);
                    }
                }
                return response()->json([
                    'success' => false,
                    'message' => 'No customer found in stripe for this campaign'
                ],200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Please Integrate stripe in is campaign'
                ],200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ],200);
        }
    }
    public function stripeCallBack(Request $request)
    {
        Log::info($request->all());
    }
}
