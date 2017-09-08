<?php

namespace App\Http\Controllers;

use App\Affiliate;
use App\AgentUrlDetails;
use App\Campaign;
use App\OrderProduct;
use App\paidCommission;
use App\PaymentHistory;
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
                    ->where('affiliate_id',Auth::user()->id)->get();
            } else {
                $comissions = paidCommission::where('affiliate_id',Auth::user()->id)->get();
            }
            $affiliateDropDown = Affiliate::where('user_id', Auth::user()->id)
                ->where('approve_status', 1);
            $campaignDropDown = Campaign::whereIn('id', $affiliateDropDown->pluck('campaign_id'))->get();
            foreach ($comissions as $comission){
                $histories = PaymentHistory::where('commission_id',$comission->id)->get();
                $primeryCommission = [];
                foreach ($histories as $history){
                    $primeryCommission['campaign_name'] = $comission->campaign->name;
                    $primeryCommission['date'] = $history->created_at;
                    $primeryCommission['amount'] = $history->amount;
                    $primeryCommission['admin'] = $comission->campaign->user->name;
                }
                array_push($finalCommission,$primeryCommission);
            }
            return view('affiliate.payout',[
                'commissions' => $finalCommission,
                'campaignDropDown' => $campaignDropDown
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
            $affiliateDropDown = Affiliate::whereIn('campaign_id',$campaignDropDown->pluck('id'))->get();
            if($filterCampaign > 0 && $filterAffiliate > 0){
                $commissions = paidCommission::where('user_id',Auth::user()->id)
                    ->where('campaign_id',$filterCampaign)
                    ->where('affiliate_id',$filterAffiliate)
                    ->orderBy('created_at','DESC')->get();
            } elseif ($filterCampaign > 0 && $filterAffiliate <= 0){
                $commissions = paidCommission::where('user_id',Auth::user()->id)
                    ->where('campaign_id',$filterCampaign)
                    ->orderBy('created_at','DESC')->get();
            } elseif ($filterCampaign <= 0 && $filterAffiliate > 0){
                $commissions = paidCommission::where('user_id',Auth::user()->id)
                    ->where('affiliate_id',$filterAffiliate)
                    ->orderBy('created_at','DESC')->get();
            } else {
                $commissions = paidCommission::where('user_id',Auth::user()->id)
                    ->orderBy('created_at','DESC')->get();
            }
            foreach ($commissions as $comission){
                $histories = PaymentHistory::where('commission_id',$comission->id)->get();
                $primeryCommission = [];
                foreach ($histories as $history){
                    $primeryCommission['campaign_name'] = $comission->campaign->name;
                    $primeryCommission['affiliate_name'] = $comission->affiliate->user->name;
                    $primeryCommission['date'] = $history->created_at;
                    $primeryCommission['amount'] = $history->amount;
                }
                array_push($finalCommission,$primeryCommission);
            }
            return view('admin.payout',[
                'commissions' => $finalCommission,
                'campaignDropDown' => $campaignDropDown,
                'affiliateDropDown' => $affiliateDropDown
            ]);
        } catch (\Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
