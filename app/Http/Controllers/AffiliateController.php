<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\AffiliateLink;
use \App\BusinessPlan;
use \App\Lead;
use \App\User;
use \App\Agency;
use \App\Jobs\AffiliatePlanSync;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function index()
    {

        $userId =  Auth::id();
        // get plans
            // get links for user
        $links = AffiliateLink::where('user_id', $userId)->get();

            // get plan names from plan table

        $totalClicks = 0;
        $totalEnrollments = 0;
        $totalSales = 0;

        foreach ($links as $key => $link) {
            if ($link->plan_id == -1) {
                continue;
            }

            $links[$key]->name = BusinessPlan::find($link->plan_id)->first()->name;

            $totalClicks += $links[$key]->clicks = Lead::where('link_id', $link->id)
                                            ->where('stage', 'click')
                                            ->count();
            $totalEnrollments += $links[$key]->enrollments = Lead::where('link_id', $link->id)
                                                 ->where('stage', 'enrollment')
                                                 ->count();

            $totalSales += $links[$key]->sales = Lead::where('link_id', $link->id)
                                           ->where('stage', 'sale')
                                           ->count();

            $links[$key]->clicks += $links[$key]->enrollments + $links[$key]->sales;

            $links[$key]->enrollments += $links[$key]->sales;
        }

        // add leads from later stages
        $totalClicks += $totalEnrollments + $totalSales;
        $totalEnrollments += $totalSales;

        $planStats = $links;

    	$leads = Lead::where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->take(10)
                          ->get();


        foreach ($leads as $key => $lead) {
            if ($lead->plan_id == -1) {
                $leads[$key]->name = 'Landing Page';
            } else {
                $leads[$key]->name = BusinessPlan::find($lead->plan_id)->first()->name;
            }
        }

        $landingPageStats = $this->getLandingPageStats();

        $vars = [
            'leads' => $leads,
            'plans' => $planStats,
            'totalClicks' => $totalClicks + $landingPageStats['clicks'],
            'totalEnrollments' => $totalEnrollments + $landingPageStats['enrollments'],
            'totalSales' => $totalSales + $landingPageStats['sales'],
            'landingPageStats' => $landingPageStats
        ];

    	return view('affiliate/dashboard', $vars);
    }

    public function links()
    {
        $userId = Auth::id();

        // get landing page url
        $user = User::find($userId);
        $agency = Agency::find($user->agency_id);
        
        if ($agency->custom_domain == '') {
            $subdomain = '';
        } else {
            $subdomain = $agency->custom_domain . '.';
        }

        // get links by user id
        $links = AffiliateLink::where('user_id', $userId)->get();

        foreach ($links as $key => $link) {
            if ($link->plan_id == -1) {
                unset($links[$key]);
                continue;
            }

            $plan = \App\BusinessPlan::find($link->plan_id);
            
            if ($plan && $plan->deleted_at == '0000-00-00 00:00:00') {
                $links[$key]->plan_name = $plan->name;
                $links[$key]->active = $plan->enabled;
                $links[$key]->deleted = false;
            } else {
                $links[$key]->deleted = true;
            }   
        }

        return view('affiliate/links', [
            'links' => $links,
            'baseUrl' => 'http://' . config('app.rv_url') . '/subscription/link/',
            'landingPageURL' => 'http://' . $subdomain . config('app.rv_url')
        ]);
    }

    public function planSync()
    {
        dispatch(new AffiliatePlanSync());
    }

    private function getLandingPageStats()
    {
        $links = AffiliateLink::where('user_id', Auth::id())
                              ->where('plan_id', -1)
                              ->get();

        $totalClicks = 0;
        $totalEnrollments = 0;
        $totalSales = 0;

        foreach ($links as $key => $link) {

            $links[$key]->name = 'Landing Page';

            $totalClicks += $links[$key]->clicks = Lead::where('link_id', $link->id)
                                            ->where('stage', 'click')
                                            ->count();
            $totalEnrollments += $links[$key]->enrollments = Lead::where('link_id', $link->id)
                                                 ->where('stage', 'enrollment')
                                                 ->count();

            $totalSales += $links[$key]->sales = Lead::where('link_id', $link->id)
                                           ->where('stage', 'sale')
                                           ->count();

            $links[$key]->clicks += $links[$key]->enrollments + $links[$key]->sales;

            $links[$key]->enrollments += $links[$key]->sales;
        }

        // add leads from later stages
        $totalClicks += $totalEnrollments + $totalSales;
        $totalEnrollments += $totalSales;

        $stats = [
            'clicks' => $totalClicks,
            'enrollments' => $totalEnrollments,
            'sales' => $totalSales
        ];


        return $stats;
    }
}
