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


Route::get('/', 'Auth\LoginController@showLoginForm');

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    //Agency Routes

    Route::get('dashboard',[
        'uses' => 'DashboardController@index',
        'as' => 'dashboard'
    ]);
    Route::get('show/affiliate',[
        'uses' => 'AffiliateController@showAffiliate',
        'as' => 'showAffiliate'
    ]);
    Route::get('add/affiliate',[
        'uses' => 'AgencyController@addAffiliate',
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
    Route::get('campaign',[
        'uses' => 'CampaignController@getCampaign',
        'as' => 'get.campaign'

    ]);
    Route::post('campaign/create',[
        'uses' => 'CampaignController@createCampaign',
        'as' => 'create.campaign'

    ]);
    Route::post('campaign/delete',[
        'uses' => 'CampaignController@deleteCampaign',
        'as' => 'delete.campaign'
    ]);
    Route::post('campaign/edit',[
        'uses' => 'CampaignController@editCampaign',
        'as' => 'edit.campaign'
    ]);
    Route::get('campaign/details/{key}',[
        'uses' => 'CampaignController@detailsCampaign',
        'as' => 'details.campaign'
    ]);

    Route::post('add/affiliate/new',[
        'uses' => 'CampaignController@addAffiliate',
        'as' => 'add.affiliate.new'
    ]);
    Route::post('approve/affiliate',[
        'uses' => 'CampaignController@approveAffiliate',
        'as' => 'approve.affiliate'
    ]);
    Route::post('affiliate/delete',[
        'uses' => 'CampaignController@deleteAffiliate',
        'as' => 'delete.affiliate'
    ]);
    Route::get('affiliate/details/{id}',[
        'uses' => 'AffiliateController@detailsAffiliate',
        'as' => 'details.affiliate'
    ]);
});

Route::get('affiliate/request/{affiliateKey}',[
    'uses' => 'CampaignController@affiliateRegistrationForm',
    'as' => 'affiliate.registerForm'
]);

Route::post('affiliate/registration',[
    'uses' => 'AffiliateController@affiliateRegistration',
    'as' => 'affiliate.registration'
]);

Route::post('affiliate/login',[
    'uses' => 'AffiliateController@affiliateLogin',
    'as' => 'affiliate.login'
]);

Route::get('thank-you',[
    'uses' => 'AffiliateController@thankYou',
    'as' => 'affiliate.thankYou'
]);

Route::get('logout',[
    'uses' => 'DashboardController@logout',
    'as' => 'logout'
]);

