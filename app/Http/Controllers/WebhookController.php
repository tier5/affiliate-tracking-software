<?php

namespace App\Http\Controllers;

use App\Affiliate;
use App\AgentUrlDetails;
use App\Campaign;
use App\CustomerRefund;
use App\Jobs\sendPurchaseEmail;
use App\Jobs\sendPurchaseUpdateEmail;
use App\OrderProduct;
use App\Product;
use App\User;
use Cartalyst\Stripe\Stripe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * For Stripe Webhooks
     * @param Request $request
     * @param $campaign_key
     */
    public function stripeCallBack(Request $request,$campaign_key)
    {
        try{
            switch ($request['type'])
            {
                case 'invoice.payment_succeeded' :
                    $subscriptionUpdate = $this->subscriptionUpdated($request,$campaign_key);
                    Log::info($subscriptionUpdate);
                    break;
                case 'charge.refunded' :
                    $refund = $this->chargeRefunded($request,$campaign_key);
                    Log::info($refund);
                    break;
                case 'invoice.upcoming':
                    $renew=$this->renewBilling($request,$campaign_key);
                    Log::info($renew);
                    break;
                case 'charge.succeeded' :
                    $sale = $this->chargeSuccess($request,$campaign_key);
                    Log::info($sale);
                    break;
                default :
                    Log::info($request['type'].' is not in use');
            }
        } catch (\Exception $exception){
            Log::info($exception->getMessage());
        }
        http_response_code(200);
    }

    /**
     * Create a New Sale when a Subscription Updated
     * @param $event
     * @param $campaign_key
     * @return string
     */
    private function subscriptionUpdated($event,$campaign_key)
    {
        try {
            $campaign = Campaign::where('key',$campaign_key)->firstOrFail();
            $customer_id = $event['data']['object']['customer'];
            if ($campaign->test_sk != '' && $campaign->test_pk != '' && $campaign->live_sk != '' && $campaign->live_pk != ''){
                if($campaign->stripe_mode == 1){
                    $key = $campaign->test_sk;
                } else {
                    $key = $campaign->live_sk;
                }
                $stripe = Stripe::make($key);
                $customer = $stripe->customers()->find($customer_id);
                $myCustomer = OrderProduct::where('email',$customer['email'])
                    ->where('first_flag',0)->firstOrFail();
                if($myCustomer->tracking_flag == 0){
                    $myCustomer->customer_id = $customer_id;
                    $myCustomer->update();
                } else {
                    $newCustomer = new OrderProduct();
                    $newCustomer->log_id = $myCustomer->log_id;
                    $newCustomer->product_id = $myCustomer->product_id;
                    $newCustomer->email = $myCustomer->email;
                    $newCustomer->status = 1;
                    $newCustomer->first_flag = 1;
                    $newCustomer->save();
                    $myCustomer->tracking_flag = 0;
                    $myCustomer->update();
                }
                return 'Customer Subscription Updated';

                /*$countCustomer = OrderProduct::where('email',$customer['email'])->count();
                if($countCustomer > 1){
                    $newCustomer = new OrderProduct();
                    $newCustomer->log_id = $myCustomer->log_id;
                    $newCustomer->product_id = $myCustomer->product_id;
                    $newCustomer->email = $myCustomer->email;
                    $newCustomer->status = 1;
                    $newCustomer->save();

                    return 'Customer Subscription Updated';
                } else {
                    return 'First Subscription';
                }*/

            } else {
                return 'Stripe is not integrated in campaign: '.$campaign->name;
            }
        } catch(\Exception $exception) {
            return $exception->getMessage();
        } catch (ModelNotFoundException $e){
            return $e->getMessage();
        }
    }

    /**
     * For Refund Events (charge.refunded)
     * @param $event
     * @param $campaign_key
     * @return string
     */
    private function chargeRefunded($event,$campaign_key)
    {
        try {
            $campaign = Campaign::where('key',$campaign_key)->firstOrFail();
            $affiliates = Affiliate::where('campaign_id',$campaign->id);
            $logs = AgentUrlDetails::where('affiliate_id',$affiliates->pluck('id'));

            $customer_email = $event['data']['object']['receipt_email'];

            $myCustomer = OrderProduct::where('email',$customer_email)
                ->whereDate('created_at',date('Y-m-d',$event['data']['object']['created']))->firstOrFail();
            $myCustomer->status = 2;
            $myCustomer->update();
            /*$refunds = new CustomerRefund();
            $refunds->campaign_id = $campaign->id;
            $refunds->log_id = $myCustomer->id;
            $refunds->amount = $event['data']['object']['refunds']['data'][0]['amount'] / 100;
            $refunds->save();*/

            return 'Refund Added Successfully';
        } catch(\Exception $exception) {
            return $exception->getMessage();
        } catch (ModelNotFoundException $e){
            return $e->getMessage();
        }
    }

    /**
     * event for invoice.upcoming action
     * @param $event
     * @param $campaign_key
     * @return string
     */
    public function renewBilling($event,$campaign_key){
        try{
            $campaign=Campaign::where('key',$campaign_key)->firstOrFail();
            $customer_id = $event['data']['object']['customer'];
            if ($campaign->test_sk != '' && $campaign->test_pk != '' && $campaign->live_sk != '' && $campaign->live_pk != '') {
                if ($campaign->stripe_mode == 1) {
                    $key = $campaign->test_sk;
                } else {
                    $key = $campaign->live_sk;
                }
                $stripe = Stripe::make($key);
                $customer = $stripe->customers()->find($customer_id);
                $myCustomer = OrderProduct::where('email', $customer['email'])
                    ->where('first_flag',0)->firstOrFail();
                $myCustomer->tracking_flag = 1;
                $myCustomer->customer_id = $customer_id;
                $myCustomer->update();
                return 'Renew Notice Success';
            }else{
                return 'Stripe is not integrated in campaign: '.$campaign->name;
            }
        }catch(\Exception $exception){
            return $exception->getMessage();
        }catch(ModelNotFoundException $e){
            return $e->getMessage();
        }
    }

    public function chargeSuccess($event,$campaign_key)
    {
        try {
            $campaign = Campaign::where('key',$campaign_key)->firstOrFail();
            $customer_id = $event['data']['object']['customer'];
            if ($campaign->test_sk != '' && $campaign->test_pk != '' && $campaign->live_sk != '' && $campaign->live_pk != ''){
                $email = $event['data']['object']['receipt_email'];
                $affiliates = Affiliate::where('campaign_id',$campaign->id);
                $log = AgentUrlDetails::where('email',$email)
                    ->whereIn('affiliate_id',$affiliates->pluck('id'))
                    ->orderBy('created_at','DESC')->firstOrFail();
                if($campaign->stripe_mode == 1){
                    $key = $campaign->test_sk;
                } else {
                    $key = $campaign->live_sk;
                }
                $stripe = Stripe::make($key);
                $subscription = $stripe->customers()->find($customer_id);
                $amount = $subscription['subscriptions']['data'][0]['plan']['amount'] / 100;
                $product = Product::where('campaign_id',$campaign->id)
                    ->where('product_price',$amount)->firstOrFail();
                $affiliate = Affiliate::find($log->affiliate_id);
                $user = User::find($affiliate->user_id);
                $user_name = $user->name;
                $user_email = $user->email;
                if ($product->method == 1) {
                    $product_commission = $product->product_price * ($product->commission / 100);
                } else {
                    $product_commission = $product->commission;
                }
                if($log != ''){
                    $previousSale = OrderProduct::where('log_id',$log->id)
                        ->where('email',$email)
                        ->where('first_flag',0)->first();
                    if($previousSale != ''){
                        $oldProductObj = Product::find($previousSale->product_id);

                        $previousSale->product_id = $product->id;
                        $previousSale->update();

                        $oldProduct = $oldProductObj->name;

                        $job = (new sendPurchaseUpdateEmail($oldProduct,$user_name,$user_email,$product->name,$amount,$product_commission,$campaign->name));
                        $this->dispatch($job);
                    } else {
                        $order = new OrderProduct();
                        $order->log_id = $log->id;
                        $order->product_id = $product->id;
                        $order->email = $email;
                        $order->save();

                        $job = (new sendPurchaseEmail($user_name,$user_email,$product->name,$amount,$product_commission,$campaign->name));
                        $this->dispatch($job);
                    }
                    $log->type = 2;
                    $log->update();
                    return 'Product Sold';
                } else {
                    return 'Customer is not coming through any affiliate';
                }
            } else {
                return 'Stripe is not integrated in campaign: '.$campaign->name;
            }
        } catch(\Exception $exception) {
            return $exception->getMessage();
        } catch (ModelNotFoundException $e){
            return $e->getMessage();
        }
    }
}