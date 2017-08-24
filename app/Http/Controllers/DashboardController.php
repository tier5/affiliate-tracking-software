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
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use App\Jobs\ValidateSubscriptions;
use \App\SubscriptionInvalidation;
use Auth;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
        if (Auth::user()->role == 'admin') {
            return $this->adminDashboard($request);
        } else if (Auth::user()->role == 'affiliate') {
            return $this->affiliateDashboard($request);
        } else {
            Auth::logout();
            return redirect('/');
        }
	}

    private function adminDashboard($request) {
        if ($request->has('campaign_id') || $request->has('affliate_id')) {
            $campaigns = collect([]);
            $affiliates = collect([]);
            $data = [
                'visitors' => collect([]),
                'leads' => collect([]),
                'sales' => collect([]),
                'totalSales' => 0,
                'chrome' => 0,
                'opera' => 0,
                'ie' => 0,
                'safari' => 0,
                'firefox' => 0,
            ];
            $latestAffiliates = collect([]);
            $products = collect([]);
            $affiliatesDropdown = collect([]);

             if ($request->input('campaign_id') == 0 && $request->input('affiliate_id') == 0) {
                return $this->adminDashboardNoFilter();
            } else if ($request->input('campaign_id') > 0 && $request->input('affiliate_id') <= 0) {
                $campaigns = Campaign::where('id', $request->input('campaign_id'))
                    ->where('user_id', Auth::user()->id);
                $campaignsDropdown = Campaign::where('user_id', Auth::user()->id)->get();
                if ($campaigns->count()) {
                    $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'));
                    $data = $this->getAnalytics($affiliates);
                    $latestAffiliates = Affiliate::where('campaign_id', $campaigns->pluck('id'))
                        ->with('user','campaign')
                        ->orderBy('created_at','DESC')->get();
                    $products = Product::where('campaign_id', $campaigns->pluck('id'))
                        ->orderBy('created_at','DESC')->take(5)->get();
                    $affiliatesDropdown = Affiliate::where('campaign_id', $campaigns->first()->id)->get();
                }
            } else if ($request->input('campaign_id') <= 0 && $request->input('affiliate_id') > 0) {
                $affiliates = Affiliate::where('id', $request->input('affiliate_id'))->with('campaign');
                if ($affiliates->first()) {
                    $campaigns = $affiliates->first()->campaign;
                }
                $data = $this->getAnalytics($affiliates);
                $latestAffiliates = $affiliates->get();
                if (count($campaigns)){
                    $products = Product::where('campaign_id', $campaigns->id)
                        ->orderBy('created_at','DESC')->take(5)->get();
                }

                $campaignsDropdown = Campaign::where('user_id', Auth::user()->id)->get();
                $affiliatesDropdown = Affiliate::whereIn('campaign_id', Campaign::where('user_id', Auth::user()->id)
                        ->pluck('id'))->get();
            } else if ($request->input('campaign_id') > 0 && $request->input('affiliate_id') > 0) {
                $campaigns = Campaign::where('id', $request->input('campaign_id'))
                    ->where('user_id', Auth::user()->id);
                if ($campaigns->count()) {
                    $affiliates = Affiliate::where('id', $request->input('affiliate_id'))
                        ->where('campaign_id', $request->input('campaign_id'));
                    $affiliate = $affiliates->first();
                    if ($affiliate) {
                        $data = $this->getAnalytics($affiliates);
                    }

                    $latestAffiliates = $affiliates->get();
                    $products = Product::where('campaign_id', $campaigns->pluck('id'))
                            ->orderBy('created_at','DESC')->take(5)->get();

                    $affiliatesDropdown = Affiliate::where('campaign_id', $campaigns->first()->id)->get();
                }
                $campaignsDropdown = Campaign::where('user_id', Auth::user()->id)->get();
            }

            return view('dashboard',[
                'campaigns' => $campaigns,
                'affiliates' => $affiliates,
                'visitors' => $data['visitors'],
                'leads' => $data['leads'],
                'sales' => $data['sales'],
                'totalSales' => $data['totalSales'],
                'chrome' => $data['chrome'],
                'opera' => $data['opera'],
                'ie' => $data['ie'],
                'safari' => $data['safari'],
                'firefox' => $data['firefox'],
                'latestAffiliates' => $latestAffiliates,
                'products' => $products,
                'affiliatesDropdown' => $affiliatesDropdown,
                'campaignsDropdown' => $campaignsDropdown
            ]);
        } else {
            return $this->adminDashboardNoFilter();
        }
    }

    private function adminDashboardNoFilter() {
        $campaigns = Campaign::where('user_id', Auth::user()->id)->with('affiliate');
        $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'));
        $data = $this->getAnalytics($affiliates);
        $latestAffiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
            ->with('user','campaign')
            ->orderBy('created_at','DESC')->get();
        $products = Product::whereIn('campaign_id',$campaigns->pluck('id'))
            ->orderBy('created_at','DESC')->take(5)->get();

        $campaignsDropdown = Campaign::where('user_id', Auth::user()->id)->get();
        $affiliatesDropdown = $affiliates->get();

        return view('dashboard',[
            'campaigns' => $campaigns,
            'affiliates' => $affiliates,
            'visitors' => $data['visitors'],
            'leads' => $data['leads'],
            'sales' => $data['sales'],
            'totalSales' => $data['totalSales'],
            'chrome' => $data['chrome'],
            'opera' => $data['opera'],
            'ie' => $data['ie'],
            'safari' => $data['safari'],
            'firefox' => $data['firefox'],
            'latestAffiliates' => $latestAffiliates,
            'products' => $products,
            'affiliatesDropdown' => $affiliatesDropdown,
            'campaignsDropdown' => $campaignsDropdown
        ]);
    }

    private function getAnalytics($affiliates) {
        $visitors = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'));
        $leads = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))->whereIn('type', [2, 3]);
        $sales = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))->where('type', 2);
        $totalSales = OrderProduct::whereIn('log_id',$sales->pluck('id'))->count();
        $chrome = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
            ->where('browser','LIKE','%Chrome%')->count();
        $opera = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
            ->where('browser','LIKE','%Opera%')->count();
        $ie = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
            ->where('browser','LIKE','%Microsoft Internet Explorer%')->count();
        $safari = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
            ->where('browser','LIKE','%Safari%')->count();
        $firefox = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
            ->where('browser','LIKE','%Firefox%')->count();

        return [
            'visitors' => $visitors,
            'leads' => $leads,
            'sales' => $sales,
            'totalSales' => $totalSales,
            'chrome' => $chrome,
            'opera' => $opera,
            'ie' => $ie,
            'safari' => $safari,
            'firefox' => $firefox,
        ];
    }

    private function affiliateDashboard($request) {
        $affiliate = Affiliate::where('user_id', Auth::user()->id)->where('approve_status',1)->get();
        $campaigns = Campaign::whereIn('id',$affiliate->pluck('campaign_id'));
        $visitors = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'));
        $leads = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))->whereIn('type', [2, 3]);
        $sales = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))->where('type', 2);
        $availableProducts = Product::whereIn('campaign_id', $campaigns->pluck('id'))->get();
        $totalSaless = OrderProduct::whereIn('log_id',$sales->pluck('id'))->count();

        /*
         * Analytics for sold products
         */
         $orderProducts = OrderProduct::whereIn('log_id', $sales->pluck('id'))->get();
         $totalSales = 0;
         $totalSalePrice = 0;
         $grossCommission = 0;
         $soldProducts = [];
         foreach ($orderProducts as $key => $order) {
             $product = Product::find($order->product_id);
             if ($product->method == 1) {
                 $myCommision = $product->product_price * ($product->commission / 100);
                 $grossCommission += $myCommision;
             } else {
                 $myCommision = $product->commission;
                 $grossCommission += $myCommision;
             }
             $soldProducts[$key]['name'] = $product->name;
             $soldProducts[$key]['unit_sold'] = 1;
             $soldProducts[$key]['total_sale_price'] = $product->product_price * 1;
             $soldProducts[$key]['my_commission'] = $myCommision;
             $totalSalePrice += $soldProducts[$key]['total_sale_price'];
             $totalSales += 1;
        }

        return view('affiliate.dashboard',[
            'affiliate' => $affiliate,
            'campaigns' => $campaigns->count(),
            'visitors' => $visitors->count(),
            'leads' => $leads->count(),
            'sales' => $sales->count(),
            'totalSales' => $totalSaless,
            'total_sale_price' => $totalSalePrice,
            'gross_commission' => $grossCommission,
            'available_products' => $availableProducts,
            'sold_products' => $soldProducts
        ]);
    }

	public function salesData(Request $request)
    {
        try {
            $campaigns = null;
            if ($request->user_type == 'affiliate') {
				$affiliates = Affiliate::where('user_id', $request->id)->where('approve_status', 1)->get();
	            $campaigns = Campaign::whereIn('id',$affiliates->pluck('campaign_id'));
            } else {
                if ($request->has('campaign_id') || $request->has('affliate_id')) {
                    if ($request->input('campaign_id') > 0 && $request->input('affiliate_id') <= 0) {
                        $campaigns = Campaign::where('id', $request->input('campaign_id'))
                            ->where('user_id', $request->id)
                            ->with('affiliate');
                        $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'));
                    } else if ($request->input('campaign_id') <= 0 && $request->input('affiliate_id') > 0) {
                        $campaigns = Campaign::where('user_id', $request->id)->with('affiliate');
                        $affiliates = Affiliate::where('id', $request->input('affiliate_id'));
                    } else if ($request->input('campaign_id') > 0 && $request->input('affiliate_id') > 0) {
                        $campaigns = Campaign::where('id', $request->input('campaign_id'))
                            ->where('user_id', $request->id)
                            ->with('affiliate');
                        $affiliates = Affiliate::where('id', $request->input('affiliate_id'));
                    } else {
                        $campaigns = Campaign::where('user_id', $request->id)
                            ->with('affiliate');
                        $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'));
                    }
                } else {
                    $campaigns = Campaign::where('user_id', $request->id)
                        ->with('affiliate');
                    $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'));
                }
            }
            $startDate = Carbon::now()->subMonth(6)->month;
            $endDate = Carbon::now()->month;
            $visitors = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
                ->where('created_at', '>=', Carbon::now()->subMonth(6))
                ->get()
                ->groupBy(function($date) {
                    //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                    return Carbon::parse($date->created_at)->format('m'); // grouping by months
                });
            $sales = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
                ->where('created_at', '>=', Carbon::now()->subMonth(6))
                ->where('type', 2)
                ->get()
                ->groupBy(function($date) {
                    //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                    return Carbon::parse($date->created_at)->format('m'); // grouping by months
                });
            $leads = AgentUrlDetails::whereIn('affiliate_id',$affiliates->pluck('id'))
                ->where('created_at', '>=', Carbon::now()->subMonth(6))
                ->where('type', [2, 3])
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
					//$salesCount[] = OrderProduct::whereIn('log_id',$sales[$dateParam]->pluck('id'))->count();
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
