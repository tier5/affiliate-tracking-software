<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;

Route::get('/', function () {

	return view('welcome');
});



//Route::get('agency', 'AgencyController@index')->name('getAdmin');
//Route::get('profile', 'AgencyController@index')->name('getProfile');
Route::post('addagency', 'AgencyController@addAgency')->name('addAgency');
//Route::get('agency/all', 'AgencyController@all');
//Route::get('agency/show/{id}', 'AgencyController@show')->name('addAgency');

Route::get('dashboard/', 'DashboardController@index');

Route::get('cron/', 'DashboardController@cron');

Route::get('validate/', 'DashboardController@run');

//Route::get('dashboard/agencies', 'DashboardController@agencies');

//Route::get('dashboard/businesses', 'DashboardController@agencies');

// auth by ip


Route::group(['prefix' => 'api'], function() {
	/*if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '192.168.33.11') {
  		die('403');
    }*/

	/*Route::get('users/{user}', function (\App\User $user) {
	    return $user;
	});*/

	Route::get('users/{agencyId}', function($agencyId) {
		return \App\User::where('agency_id', $agencyId)->get();

		// get links where agency id = $agencyId
		// group by user_id, sum('clicks, enrollments, sales')
	});

	Route::get('user_by_email/{email}', function($email) {
		$user = \App\User::withTrashed()
						 ->where('email', $email)
						 ->first();

		if ($user == NULL) {
			return [];
		}

		if ($user->trashed()) {
			$user->restore();
		}

		return $user;
	});

	Route::get('user/clicks/{userId}', function($userId) {
		return \App\Lead::where('user_id', $userId)
				 		->where('stage', 'click')
				 		->count();
	});

	Route::get('user/enrollments/{userId}', function($userId) {
		return \App\Lead::where('user_id', $userId)
				 		->where('stage', 'enrollment')
				 		->count();
	});

	Route::get('user/sales/{userId}', function($userId) {
		return \App\Lead::where('user_id', $userId)
				 		->where('stage', 'sale')
				 		->count();
	});

	Route::get('plan/clicks/{planId}', function($planId) {
		return \App\Lead::where('plan_id', $planId)
				 		->where('stage', 'click')
				 		->count();
	});

	Route::get('plan/enrollments/{planId}', function($planId) {
		return \App\Lead::where('plan_id', $planId)
				 		->where('stage', 'enrollment')
				 		->count();
	});

	Route::get('plan/sales/{planId}', function($planId) {
		return \App\Lead::where('plan_id', $planId)
				 		->where('stage', 'sale')
				 		->count();
	});

	



	// update individual user
	Route::put('user/{user}', function(Request $request, $user) {
		$user = \App\User::find($user);
		$user->fill($request->all());
		$user->save();

		return $user;
	});

	// create user
	Route::post('user', function(Request $request) {
		$data = $request->all();
		$data['password'] = bcrypt($data['password']);


		$user = \App\User::create(
			$data
		);

		return $user;
	});

	// get user
	Route::get('user/{userId}', function($userId) {
		$user = \App\User::find($userId);

		return $user;
	});

	// delete user
	Route::delete('user/{userId}', function($userId) {
		return \App\User::destroy($userId);
	});

	// create lead
	Route::post('lead', function(Request $request) {

		$lead = \App\Lead::create(
			$request->all()
		);

		return $lead;
	});

	// update lead
	Route::put('lead/{lead}', function(Request $request, $lead) {
		$lead = \App\Lead::find($lead);
		$lead->fill($request->all());
		$lead->save();

		return $lead;
	});

	// get affiliate link by code
	Route::get('link/{code}', function($code) {
		$lead = \App\AffiliateLink::where('code', $code)->get();

		// get sharing code from sharing_code table

		return $lead;
	});

	// create affiliate link
	Route::post('link', function(Request $request) {
        $data = $request->all();

		$link = \App\AffiliateLink::where('user_id', $data['user_id'])
						  ->where('plan_id', $data['plan_id'])
						  ->get();

		if ($link != null and count($link) != 0) {
			return false;
		}

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $short_code = '';

        while (1) {
            for ($i = 0; $i < 12; $i++) {
                $short_code .= $characters[mt_rand(0, strlen($characters) - 1)];
            }

            $link = \App\AffiliateLink::where('code', $short_code)->first();
            
            if (!$link) {
                break;
            }
        }

        $data['code'] = $short_code;

		$link = \App\AffiliateLink::create(
			$data
		);

		return $link;
	});

	Route::get('landingPageStats/{agencyId}', function($agencyId) {


		// need to add up all affiliates stats
		$users = \App\User::where('agency_id', $agencyId)->get();

		//return $users;

        $totalClicks = 0;
        $totalEnrollments = 0;
        $totalSales = 0;

        foreach ($users as $user) {
            $totalClicks += \App\Lead::where('plan_id', -1)
            					->where('user_id', $user->id)
                                ->where('stage', 'click')
                                ->count();

            $totalEnrollments += \App\Lead::where('plan_id', -1)
                                     ->where('stage', 'enrollment')
                                     ->where('user_id', $user->id)
                                     ->count();

            $totalSales += \App\Lead::where('plan_id', -1)
            				   ->where('user_id', $user->id)
                               ->where('stage', 'sale')
                               ->count();
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
	});

	// generate affiliate links




	// get affiliate report
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard/agency',[
        'uses' => 'AffiliateController@index',
        'as' => 'agency.dashboard'
    ]);
    Route::get('show/affiliate',[
        'uses' => 'AffiliateController@showAffiliate',
        'as' => 'showAffiliate'
    ]);
    Route::get('add/affiliate',[
        'uses' => 'AgencyController@index',
        'as' => 'get.add.affiliate'
    ]);
    Route::get('allAffiliate',[
        'uses' => 'AffiliateController@allAffiliate',
        'as' => 'allAffiliate'
    ]);
    Route::post('addaffiliate',[
        'uses' => 'AffiliateController@addAffiliate',
        'as' => 'addAffiliate'
    ]);
    Route::get('settings',[
        'uses' => 'AgencyController@getSettings',
        'as' => 'settings'
    ]);
    Route::post('register/url',[
        'uses' => 'AgencyController@registerUrl',
        'as' => 'register.url'
    ]);
    Route::get('affiliates/{affiliateId}',[
        'uses' => 'AgencyController@showAffiliate',
        'as' => 'agency.affiliateDetail'
    ]);
    Route::get('affiliate/{affiliateKey}',[
        'uses' => 'AgencyController@affiliateDashboard',
        'as' => 'agency.affiliateDashboard'
    ]);
});


//Route::get('agency', 'AgencyController@index')->name('getAdmin');

//Route::get('affiliate/links', 'AffiliateController@links');

Route::get('planSync', 'AffiliateController@planSync');

Route::get('/home', 'HomeController@index')->name('home');

