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
use App\SalesDetail;
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
                if ($customer->product->method == 1) {
                    $product_commission = $customer->product->product_price * ($customer->product->commission / 100);
                } else {
                    $product_commission = $customer->product->commission;
                }

                if($myCustomer->tracking_flag == 0){
                    $myCustomer->customer_id = $customer_id;
                    $myCustomer->update();
                } else {
                    /*$newCustomer = new OrderProduct();
                    $newCustomer->log_id = $myCustomer->log_id;
                    $newCustomer->product_id = $myCustomer->product_id;
                    $newCustomer->email = $myCustomer->email;
                    $newCustomer->status = 1;
                    $newCustomer->first_flag = 1;
                    $newCustomer->save();
                    $myCustomer->tracking_flag = 0;
                    $myCustomer->update();*/

                    $salesData = new SalesDetail();
                    $salesData->sales_id = $myCustomer->id;
                    $salesData->product_id = $myCustomer->product->id;
                    $salesData->product_amount = $myCustomer->product->product_price;
                    $salesData->step_payment_amount = $myCustomer->product->product_price;
                    $salesData->charge_id = $event['data']['object']['charge'];
                    $salesData->type = 1;
                    $salesData->status = 3;
                    $salesData->commission = $product_commission;
                    $salesData->save();
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
            $charge_id = $event['data']['object']['id'];
            $sales = SalesDetail::where('charge_id',$charge_id)->firstOrFail();
            $sales->type = 2;
            $sales->update();
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
    public function renewBilling($event,$campaign_key)
    {
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

    /**
     * Event for charge.succeeded action
     * @param $event
     * @param $campaign_key
     * @return string
     */
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

                        $salesData = new SalesDetail();
                        $salesData->sales_id = $previousSale->id;
                        $salesData->product_id = $product->id;
                        $salesData->product_amount = $product->product_price;
                        $salesData->step_payment_amount = $event['data']['object']['amount'] / 100;
                        $salesData->charge_id = $event['data']['object']['id'];
                        $salesData->type = 1;
                        $salesData->status = 2;
                        $salesData->commission = $product->upgrade_commission;
                        $salesData->save();

                        $oldProduct = $oldProductObj->name;

                        $job = (new sendPurchaseUpdateEmail($oldProduct,$user_name,$user_email,$product->name,$amount,$product_commission,$campaign->name));
                        $this->dispatch($job);
                    } else {
                        $order = new OrderProduct();
                        $order->log_id = $log->id;
                        $order->product_id = $product->id;
                        $order->email = $email;
                        $order->save();

                        $salesData = new SalesDetail();
                        $salesData->sales_id = $order->id;
                        $salesData->product_id = $product->id;
                        $salesData->product_amount = $product->product_price;
                        $salesData->step_payment_amount = $event['data']['object']['amount'] / 100;
                        $salesData->charge_id = $event['data']['object']['id'];
                        $salesData->type = 1;
                        $salesData->status = 1;
                        $salesData->commission = $product->upgrade_commission;
                        $salesData->save();

                        $job = (new sendPurchaseEmail($user_name,$user_email,$product->name,$amount,$product_commission,$campaign->name));
                        $this->dispatch($job);
                    }
                    $log->type = 2;
                    $log->update();

                    return 'Product Sold from stripe';
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

    /**
     * Event for create a subscriber
     * @param email, token
     * @return response
     */
    public function createUserByEmail(Request $request)
    {
      $token = $request->token;
      $email = $request->email;
      try {

        $v = \Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
        ]);
        if($v->fails()) {
          return \Response::json([
            "http_code" => 400,
            "status"    => "error",
            "message"   => "email address format is incorrect or is already present!"
          ],400);
        }

        if ($token != config('api.token')) {
          return \Response::json([
            "http_code" => 400,
            "status"    => "error",
            "message"   => "Authentication token incorrect"
          ],400);
        } else {

          $name   = explode('@',$email);
          $name   = $name[0];

          $user                 = new User();
          $user->name           = $name;
          $user->email          = $email;
          $user->status         = '1';
          $user->role           = 'affiliate';
          $user->password       = bcrypt(config('api.default_password'));
          $user->remember_token = '';

          if($user->save()) {

            return \Response::json([
              "http_code" => 200,
              "status"    => "success",
              "message"   => "User created successfully with default password!"
            ],200);

          } else {
            return \Response::json([
              "http_code" => 500,
              "status"    => "error",
              "message"   => "Database connectivity error.. Please try after sometime!"
            ],500);
          }

        }

      } catch (Exception $e) {
        return \Response::json([
          "http_code" => 500,
          "status"    => "error",
          "message"   => $e->getMessage()
        ],500);
      }
    }

    public function deleteUserByEmail(Request $request)
    {
      $token = $request->token;
      $email = $request->email;
      try {
        if ($token != config('api.token')) {
          return \Response::json([
            "http_code" => 400,
            "status"    => "error",
            "message"   => "Authentication token incorrect"
          ],400);
        } else {
          $user = User::where('email', $email)->first();
          if ($user) {
            if ($user->delete()) {
              return \Response::json([
                "http_code" => 200,
                "status"    => "error",
                "message"   => "User delete successfull"
              ],200);
            } else {
              return \Response::json([
                "http_code" => 500,
                "status"    => "error",
                "message"   => "Database error"
              ],500);
            }
          } else {
            return \Response::json([
              "http_code" => 400,
              "status"    => "error",
              "message"   => "User not found"
            ],400);
          }
        }

      } catch (Exception $e) {
        return \Response::json([
          "http_code" => 500,
          "status"    => "error",
          "message"   => $e->getMessage()
        ],500);
      }

    }
}
