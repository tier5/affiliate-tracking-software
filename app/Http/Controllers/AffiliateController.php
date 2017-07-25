<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\AffiliateLink;
use \App\BusinessPlan;
use \App\Lead;
use \App\User;
use \App\Agency;
use \App\Affiliate;
use \App\Visitor;
use \App\Jobs\AffiliatePlanSync;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class AffiliateController extends Controller
{
    public function index()
    {
        return view('agency.dashboard');
    }

    public function showAffiliate()
    {
        $user = Auth::user();

        $result =[
                    'name' => $user->name,
                    'email' => $user->email,
                    'url' => $user->url
        ];
        
        return view('agency.dashboard',compact('result'));
    }

    public function links()
    {
        //echo 9999; die;
        //echo $_POST['affilatedID'];
        $affiliateid = $_POST['affilatedID'];
        $affiliateip = $_POST['affilatedIP'];
        $affilatedbrowser = $_POST['affilatedbrowser'];
        
        //$user = \App\Affiliate::find($userId);
        $affiliateID = Affiliate::where('affiliate_key', $affiliateid)->first();
        echo $affiliateID ->affiliate_id;
        $visitorrecord = new Visitor;
        $visitorrecord ->affiliate_id = $affiliateID ->affiliate_id;
        $visitorrecord ->visit_count = 1;
        $visitorrecord ->affiliate_ip = $affiliateip;
        $visitorrecord ->affiliated_browser = $affilatedbrowser;
        $visitorrecord->save();

        die;

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

    

    public function addAffiliate(Request $request)
    {
        
        $data = Input::all();
        //$regex = '/^(http?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'phone' => 'required|numeric|digits_between:10,10',
            'email' => 'required|email|min:8|unique:users_laravel',
            //'url' => 'regex:' . $regex,
            
        ],
        [
            'name.required' => 'Affilator name is required',
            'phone.required' => 'Phone is required',
            'phone.numeric' => 'Phone number should be numeric',
            'phone.digits_between' => 'Phone number should be min and max 10 digit',
            'email.email' => 'Correct email format required',
            //'url.regex' => 'Url format is incorrect',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->route('get.add.affiliate')
                ->withErrors($validator)
                ->withInput();
        } else{
            $user = new User();
            $user->name = $data['name'];
            $user->role = 'Affiliate';
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();

            $affiliate = new Affiliate;
            $affiliate->userid = $user->id;
            $affiliate->affiliate_key = $data['key'];
            $affiliate->affiliate_url = $data['url'];
            $affiliate->affiliate_phone = $data['phone'];
            $affiliate->affiliate_description = $data['description'];
            $affiliate->save();

            $userAuth = Auth::user();
            $result =[
                    'name' => $userAuth->name,
                    'email' => $userAuth->email,
                    'url' => $userAuth->url ];
            return view('agency.addAffiliate',compact('result'))->with('message', 'Affiliate created successfully with url  '.$data['url']);

        }
    }

    
    public function allAffiliate()
    {
        $user = \App\User::where('role','Affiliate')->with('affiliate')->get();
        $data[]= array();
        foreach($user as $key=>$affiliates){
            $data[$key]['id']= $affiliates->id;
            $data[$key]['name']= $affiliates->name;
            $data[$key]['phone']= $affiliates->affiliate->affiliate_phone;
            $data[$key]['url']= $affiliates->affiliate->affiliate_url;
            $data[$key]['joined']= $affiliates->created_at;
        }

        return view('agency.allAffiliate',compact('data'));
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
