<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Jobs\ValidateSubscriptions;
use \App\SubscriptionInvalidation;
use Auth;

class DashboardController extends Controller
{
	public function index()
	{
        return view('dashboard');
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
