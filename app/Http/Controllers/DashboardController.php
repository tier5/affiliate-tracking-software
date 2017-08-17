<?php

namespace App\Http\Controllers;

use App\Affiliate;
use App\AgentUrlDetails;
use App\Campaign;
use App\OrderProduct;
use App\Product;
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
        if (Auth::user()->role == 'admin') {
            $campaigns = Campaign::where('user_id',Auth::user()->id)->with('affiliate');
            $affiliates=Affiliate::whereIn('campaign_id', $campaigns->pluck('id'));
            $visitors = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))->where('type',1);
            $leads = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))->where('type',3);
            $sales = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))->where('type',2);
			$totalSales = OrderProduct::whereIn('log_id',$sales->pluck('id'))->count();
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
            $latestAffiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
                ->with('user','campaign')
                ->orderBy('created_at','DESC')->get();
            $products = Product::whereIn('campaign_id',$campaigns->pluck('id'))
                ->orderBy('created_at','DESC')->take(5)->get();

            return view('dashboard',[
                'campaigns' => $campaigns,
                'affiliates' => $affiliates,
                'visitors' => $visitors,
                'leads' => $leads,
                'sales' => $sales,
				'totalSales' => $totalSales,
                'chrome' => $chrome,
                'opera' => $opera,
                'ie' => $ie,
                'safari' => $safari,
                'firefox' => $firefox,
                'latestAffiliates' => $latestAffiliates,
                'products' => $products
            ]);
        } else if (Auth::user()->role == 'affiliate') {
            $affiliate = Affiliate::where('user_id', Auth::user()->id)->where('approve_status',1)->get();
            $campaigns = Campaign::whereIn('id',$affiliate->pluck('campaign_id'));
            $visitors = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))->where('type',1);
            $leads = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))->where('type',3);
            $sales = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))->where('type',2);
            $availableProducts = Product::whereIn('campaign_id', $campaigns->pluck('id'))->get();
			$totalSales = OrderProduct::whereIn('log_id',$sales->pluck('id'))->count();

            /*
             * Analytics for sold products
             */
            $orderedProducts = OrderProduct::whereIn('log_id', $sales->pluck('id'));
            $products = Product::whereIn('id', $orderedProducts->pluck('product_id'));
            $totalSales = 0;
            $totalSalePrice = 0;
            $grossCommission = 0;
            $soldProducts = [];
            foreach ($products->get() as $key => $product) {
                $totalUnitSold = OrderProduct::whereIn('product_id', [$product->id])->groupBy('product_id')->count();
                if ($product->method == 1) {
                    $myCommision = $product->product_price * ($product->commission / 100);
                    $grossCommission += $myCommision;
                } else {
                    $myCommision = $product->commission;
                    $grossCommission += $myCommision;
                }
                $soldProducts[$key]['name'] = $product->name;
                $soldProducts[$key]['unit_sold'] = $totalUnitSold;
                $soldProducts[$key]['total_sale_price'] = $product->product_price * $totalUnitSold;
                $soldProducts[$key]['my_commission'] = $myCommision;
                $totalSalePrice += $soldProducts[$key]['total_sale_price'];
                $totalSales += $totalUnitSold;
            }
            return view('affiliate.dashboard',[
                'affiliate' => $affiliate,
                'campaigns' => $campaigns->count(),
                'visitors' => $visitors->count(),
                'leads' => $leads->count(),
                'sales' => $totalSales,
				'totalSales' => $totalSales,
                'total_sale_price' => $totalSalePrice,
                'gross_commission' => $grossCommission,
                'available_products' => $availableProducts,
                'sold_products' => $soldProducts
            ]);
        } else {
            Auth::logout();
            return redirect('/');
        }
	}
	public function salesData(Request $request)
    {
        try{
            $campaigns = null;
            if ($request->user_type == 'affiliate') {
				$affiliates = Affiliate::where('user_id', $request->id)->where('approve_status',1)->get();
	            $campaigns = Campaign::whereIn('id',$affiliates->pluck('campaign_id'));
            } else {
                $campaigns = Campaign::where('user_id', $request->id)->with('affiliate');
				$affiliates=Affiliate::whereIn('campaign_id', $campaigns->pluck('id'));
            }
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

            // $totalSales = 0;
            // $sales = AgentUrlDetails::whereIn('affiliate_id', $affiliates->pluck('id'))->where('type',2);
            // $orderedProducts = OrderProduct::whereIn('log_id', $sales->pluck('id'))
            //         ->get()->groupBy(function($date) {
            //             return Carbon::parse($date->created_at)->format('m'); // grouping by months
            //         });
            // for ($i = $startDate; $i <= $endDate; $i++) {
            //     if(strlen($i) == 1){
            //         $dateParam = '0'.$i;
            //     } else {
            //         $dateParam = $i;
            //     }
            //     if(isset($orderedProducts[$dateParam])){
            //         $products = Product::whereIn('id', $orderedProducts[$dateParam]->pluck('product_id'));
            //         foreach ($products->get() as $key => $product) {
            //             $totalUnitSold = OrderProduct::whereIn('product_id', $products->pluck('id'))->groupBy('product_id')->count();
            //             $totalSales += $totalUnitSold;
            //         }
            //         $salesCount[] = $totalSales;
            //     } else {
            //         $salesCount[] = 0;
            //     }
            // }
            // dd($salesCount);

            return response()->json([
                'success' => true,
                'months' => $month,
                'visitors' => $visitorsCount,
                'leads' => $leadsCount,
                'sales' => $salesCount
            ],200);
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
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
