<?php

namespace App\Http\Controllers;

use App\Affiliate;
use App\AgentUrlDetails;
use App\Campaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Jobs\ValidateSubscriptions;
use \App\SubscriptionInvalidation;
use Auth;

class DashboardController extends Controller
{
	public function index()
	{
	    $campaigns = Campaign::where('user_id',Auth::user()->id)->with('affiliate');
        $affiliates=Affiliate::whereIn('campaign_id', $campaigns->pluck('id'));
        $visitors = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'));
        $leads = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))->where('type',3);
        $sales = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))->where('type',2);
        $chrome = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
            ->where('browser','LIKE','%Chrome%')->count();
        $opera = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
            ->where('browser','LIKE','%Opera%')->count();
        $ie = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
            ->where('browser','LIKE','%MSIE%')->count();
        $safari = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
            ->where('browser','LIKE','%Safari%')->count();
        $firefox = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
            ->where('browser','LIKE','%Firefox%')->count();

        return view('dashboard',[
            'campaigns' => $campaigns,
            'affiliates' => $affiliates,
            'visitors' => $visitors,
            'leads' => $leads,
            'sales' => $sales,
            'chrome' => $chrome,
            'opera' => $opera,
            'ie' => $ie,
            'safari' => $safari,
            'firefox' => $firefox
        ]);
	}
	public function salesData(Request $request)
    {
        try{
            $campaigns = Campaign::where('user_id',$request->id)->with('affiliate');
            $affiliates=Affiliate::whereIn('campaign_id', $campaigns->pluck('id'));
            $startDate = Carbon::now()->subMonth(6)->month;
            $endDate = Carbon::now()->month;
            $visitors = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
                ->where('created_at', '>=', Carbon::now()->subMonth(6))
                ->where('type',1)
                ->get()
                ->groupBy(function($date) {
                    //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                    return Carbon::parse($date->created_at)->format('m'); // grouping by months
                });
            $sales = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
                ->where('created_at', '>=', Carbon::now()->subMonth(6))
                ->where('type',2)
                ->get()
                ->groupBy(function($date) {
                    //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                    return Carbon::parse($date->created_at)->format('m'); // grouping by months
                });
            $leads = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
                ->where('created_at', '>=', Carbon::now()->subMonth(6))
                ->where('type',3)
                ->get()
                ->groupBy(function($date) {
                    //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                    return Carbon::parse($date->created_at)->format('m'); // grouping by months
                });
            for ($i=$startDate;$i <= $endDate;$i++){
                if(strlen($i) == 1){
                    $dateParam = '0'.$i;
                } else {
                    $dateParam = $i;
                }
                if(isset($visitors[$dateParam])){
                    $visitorsCount[] = count($visitors[$dateParam]);
                } else {
                    $visitorsCount[] = 0;
                }
                if(isset($sales[$dateParam])){
                    $salesCount[] = count($sales[$dateParam]);
                } else {
                    $salesCount[] = 0;
                }
                if(isset($leads[$dateParam])){
                    $leadsCount[] = count($leads[$dateParam]);
                } else {
                    $leadsCount[] = 0;
                }
                $month[] = date("F", mktime(0, 0, 0, $dateParam, 1));
            }
            return response()->json([
                'success' => true,
                'months' => $month,
                'visitors' => $visitorsCount,
                'leads' => $leadsCount,
                'sales' => $salesCount
            ],200);
        } catch (\Exception $e){
            return response()->json([
                'success' => true,
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function agencies()
    {
    	
    }

    public function businesses()
    {

    }

    public function run()
    {
        dispatch(new ValidateSubscriptions());
    }

    public function cron()
    {
        $subscriptions = SubscriptionInvalidation::all();

        return view('admin/cron', ['subscriptions' => $subscriptions]);
    }

    public function approve()
    {

    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
