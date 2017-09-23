<?php

namespace App\Http\Controllers;

use App\Affiliate;
use App\AgentUrlDetails;
use App\Campaign;
use App\CustomerRefund;
use App\OrderProduct;
use App\paidCommission;
use App\PaymentHistory;
use App\SalesDetail;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class PaymentController extends Controller
{
    public function affiliatePayout()
    {
        try{
            $finalCommission = [];
            $filterCampaign = Input::get('campaign');
            if($filterCampaign > 0){
                $comissions = paidCommission::where('campaign_id',$filterCampaign)
                    ->where('affiliate_id',Auth::user()->id)->orderBy('created_at','DESC')->get();
                $affiliates = Affiliate::where('user_id', Auth::user()->id)
                    ->where('approve_status', 1)
                    ->where('campaign_id',$filterCampaign)->orderBy('created_at','DESC')->get();
            } else {
                $comissions = paidCommission::where('affiliate_id',Auth::user()->id)->orderBy('created_at','DESC')->get();
                $affiliates = Affiliate::where('user_id', Auth::user()->id)
                    ->where('approve_status', 1)->orderBy('created_at','DESC')->get();
            }
            $affiliateDropDown = Affiliate::where('user_id', Auth::user()->id)
                ->where('approve_status', 1);
            $campaignDropDown = Campaign::whereIn('id', $affiliateDropDown->pluck('campaign_id'))->get();
            $totalCommission = 0;
            $netCommission = 0;
            foreach ($comissions as $comission){
                $histories = PaymentHistory::where('commission_id',$comission->id)->orderBy('created_at','DESC')->get();
                $primeryCommission = [];
                foreach ($histories as $history){
                    $primeryCommission['campaign_name'] = $comission->campaign->name;
                    $primeryCommission['date'] = $history->created_at;
                    $primeryCommission['amount'] = $history->amount;
                    $primeryCommission['admin'] = $comission->campaign->user->name;
                    array_push($finalCommission,$primeryCommission);
                }
                $totalCommission = $totalCommission + $comission->paid_commission;
            }
            $logs = AgentUrlDetails::whereIn('affiliate_id', $affiliates->pluck('id'))
                ->where('type', 2);
            $orderObj = OrderProduct::whereIn('log_id', $logs->pluck('id'))
                ->orderBy('created_at', 'DESC')
                ->with('product');
            $newSalesData = SalesDetail::whereIn('sales_id',$orderObj->pluck('id'))->get();
            $newGrossCommission = 0;
            $newRefund = 0;
            $refundCountNew = 0;
            foreach ($newSalesData as $value){
                if($value->type == 1){
                    $newGrossCommission = $newGrossCommission + $value->commission;
                } else {
                    $newRefund = $newRefund + $value->commission;
                    $refundCountNew = $refundCountNew + 1;
                }
            }
            $refunds = CustomerRefund::whereIn('log_id',$orderObj->pluck('id'))->get();
            $totalCommissionRefund = 0 ;
            foreach ($refunds as $refund){
                $refundLog = OrderProduct::find($refund->log_id);
                $product = $refundLog->product;
                if($product->method == 1){
                    $commission = $refund->amount * ($product->commission / 100);
                } else {
                    $commission = $refund->amount * ($product->commission / $product->product_price);
                }
                $totalCommissionRefund += $commission;
            }
            $orderProducts = $orderObj->get();
            $grossCommission = 0;
            $refundCommission = 0;
            $refundCount = 0;
            foreach ($orderProducts as $key => $order) {
                if ($order->product->method == 1) {
                    $myCommision = $order->product->product_price * ($order->product->commission / 100);
                    $grossCommission += $myCommision;
                    if ($order->status == 2) {
                        $refundCommission = $refundCommission + $myCommision;
                    }
                } else {
                    $myCommision = $order->product->commission;
                    $grossCommission += $myCommision;
                    if ($order->status == 2) {
                        $refundCommission = $refundCommission + $myCommision;
                    }
                }
            }
            $netCommission = $newGrossCommission - $newRefund;
            return view('affiliate.payout',[
                'commissions' => $finalCommission,
                'campaignDropDown' => $campaignDropDown,
                'totalPaid' => $totalCommission,
                'netCommission' => $netCommission
            ]);
        } catch (\Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
    public function adminPayout()
    {
        try {
            $finalCommission = [];
            $filterCampaign = Input::get('campaign');
            $filterAffiliate = Input::get('affiliate');
            $campaignDropDown = Campaign::where('user_id',Auth::user()->id)->get();
            if($filterCampaign > 0 && $filterAffiliate > 0){
                $commissions = paidCommission::where('user_id',Auth::user()->id)
                    ->where('campaign_id',$filterCampaign)
                    ->where('affiliate_id',$filterAffiliate)
                    ->orderBy('created_at','DESC')->get();
                $affiliates = Affiliate::where('campaign_id', $filterCampaign)
                    ->where('approve_status', 1)
                    ->where('user_id', $filterAffiliate)
                    ->orderBy('campaign_id', 'ASC');
                $affiliateDropDown = Affiliate::whereIn('campaign_id',$campaignDropDown->pluck('id'))->get();
            } elseif ($filterCampaign > 0 && $filterAffiliate <= 0){
                $campaigns = Campaign::where('id', $filterCampaign);
                $commissions = paidCommission::where('user_id',Auth::user()->id)
                    ->where('campaign_id',$filterCampaign)
                    ->orderBy('created_at','DESC')->get();
                $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
                    ->where('approve_status', 1)
                    ->orderBy('campaign_id', 'ASC');
                $affiliateDropDown = Affiliate::whereIn('campaign_id',$campaigns->pluck('id'))->get();
            } elseif ($filterCampaign <= 0 && $filterAffiliate > 0){
                $commissions = paidCommission::where('user_id',Auth::user()->id)
                    ->where('affiliate_id',$filterAffiliate)
                    ->orderBy('created_at','DESC')->get();
                $affiliates = Affiliate::where('user_id', $filterAffiliate)
                    ->where('approve_status', 1)
                    ->orderBy('campaign_id', 'ASC');
                $affiliateDropDown = Affiliate::whereIn('campaign_id',$campaignDropDown->pluck('id'))->get();
            } else {
                $commissions = paidCommission::where('user_id',Auth::user()->id)
                    ->orderBy('created_at','DESC')->get();
                $campaigns = Campaign::where('user_id', Auth::user()->id);
                $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
                    ->where('approve_status', 1)
                    ->orderBy('campaign_id', 'ASC');
                $affiliateDropDown = Affiliate::whereIn('campaign_id',$campaignDropDown->pluck('id'))->get();
            }
            $totalCommission = 0;
            $netCommission = 0;
            foreach ($commissions as $comission){
                $histories = PaymentHistory::where('commission_id',$comission->id)->orderBy('created_at','DESC')->get();
                $affiliate = User::find($comission->affiliate_id);
                $primeryCommission = [];
                foreach ($histories as $history){
                    $primeryCommission['campaign_name'] = $comission->campaign->name;
                    $primeryCommission['affiliate_name'] = $affiliate->name;
                    $primeryCommission['date'] = $history->created_at;
                    $primeryCommission['amount'] = $history->amount;
                    array_push($finalCommission,$primeryCommission);
                }
                $totalCommission = $totalCommission + $comission->paid_commission;
            }
            $logs = AgentUrlDetails::whereIn('affiliate_id', $affiliates->pluck('id'))
                ->where('type', 2);
            $orderObj = OrderProduct::whereIn('log_id', $logs->pluck('id'))
                ->orderBy('created_at', 'DESC')
                ->with('product');
            $newSalesData = SalesDetail::whereIn('sales_id',$orderObj->pluck('id'))->get();
            $newGrossCommission = 0;
            $newRefund = 0;
            $refundCountNew = 0;
            foreach ($newSalesData as $value){
                if($value->type == 1){
                    $newGrossCommission = $newGrossCommission + $value->commission;
                } else {
                    $newRefund = $newRefund + $value->commission;
                    $refundCountNew = $refundCountNew + 1;
                }
            }
            $refunds = CustomerRefund::whereIn('log_id',$orderObj->pluck('id'))->get();
            $totalCommissionRefund = 0 ;
            foreach ($refunds as $refund){
                $refundLog = OrderProduct::find($refund->log_id);
                $product = $refundLog->product;
                if($product->method == 1){
                    $commission = $refund->amount * ($product->commission / 100);
                } else {
                    $commission = $refund->amount * ($product->commission / $product->product_price);
                }
                $totalCommissionRefund += $commission;
            }
            $orderProducts = $orderObj->get();
            $grossCommission = 0;
            $refundCommission = 0;
            $refundCount = 0;
            foreach ($orderProducts as $key => $order) {
                if ($order->product->method == 1) {
                    $myCommision = $order->product->product_price * ($order->product->commission / 100);
                    $grossCommission += $myCommision;
                    if ($order->status == 2) {
                        $refundCommission = $refundCommission + $myCommision;
                    }
                } else {
                    $myCommision = $order->product->commission;
                    $grossCommission += $myCommision;
                    if ($order->status == 2) {
                        $refundCommission = $refundCommission + $myCommision;
                    }
                }
            }
            $netCommission = $newGrossCommission - $newRefund;
            return view('admin.payout',[
                'commissions' => $finalCommission,
                'campaignDropDown' => $campaignDropDown,
                'affiliateDropDown' => $affiliateDropDown,
                'totalPaid' => $totalCommission,
                'netCommission' => $netCommission
            ]);
        } catch (\Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
    public function refundDetails($id)
    {
        try {
            $refunds = CustomerRefund::where('log_id',$id)->get();
            return view('admin.refund',[
                'refunds' => $refunds
            ]);
        } catch (\Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
