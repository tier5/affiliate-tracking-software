<?php

namespace App\Http\Controllers;

use App\AgentUrl;
use App\AgentUrlDetails;
use App\Campaign;
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
    public function affiliateRegistration(Request $request){
        $validator=Validator::make($request->all(),[
            'registration_username' => 'required',
            'registration_email' => 'required|email|unique:users,email',
            'registration_password' => 'required|min:6',
            'registration_confirm_password' => 'required|same:registration_password',
        ],[
            'registration_username.required' => 'Username is required',
            'registration_email.required' => 'Email Address is required',
            'registration_email.unique' => 'You are already a registered user with this Email Address.',
            'registration_password.required' => 'Registration Password is required',
            'registration_password.min' => 'Registration Password must have a minimum of 6 characters',
            'registration_confirm_password.required' => 'Confirmation Password is required',
            'registration_confirm_password.same' => 'Registration Password and Confirmation Password does not match',
        ]);

        if($validator->fails()){
            $errors=$validator->errors();
            $errors->add('registration_errors', 'This is a registration error indicator');
            return redirect()->route('affiliate.registerForm',['affiliateKey' => $request->affiliateKey])->withErrors($errors)->withInput();
        }else{

            $user=new User();
            $user->name=$request->registration_username;
            $user->email=$request->registration_email;
            $user->password=bcrypt($request->registration_password);
            $user->status='1';
            $user->role='affiliate';
            if($user->save()){
                $campaignObj=Campaign::where('key',$request->affiliateKey)->first();
                $affiliate=new Affiliate();
                $affiliate->campaign_id=$campaignObj->id;
                $affiliate->user_id=$user->id;
                $affiliate->key=$this->generateRandomString(16);
                switch($campaignObj->approval){
                    case '1':
                            $affiliate->approve_status = 1;
                            if($affiliate->save()) {
                                if (Auth::attempt(['email' => $user->email, 'password' => $user->password, 'status' => '1','role' => 'affiliate'])) {
                                    return redirect()->route('dashboard');
                                } else {
                                    return redirect()->route('affiliate.registerForm', ['affiliateKey', $request->affiliateKey])->with('flash', ['message' => 'Unable To Register User!', 'level' => 'danger']);
                                }
                            }else{
                                return redirect()->route('affiliate.registerForm',['affiliateKey',$request->affiliateKey])->with('error','Unable To Register User!');
                            }
                            break;
                    case '2':
                            $affiliate->approve_status = 2;
                            if($affiliate->save()) {
                                return redirect()->route('affiliate.thankYou');
                            }else{
                                return redirect()->route('affiliate.registerForm',['affiliateKey',$request->affiliateKey])->with('error','Unable To Register User!');

                            }
                            break;
                }
            }else{
                return redirect()->route('affiliate.registerForm',['affiliateKey',$request->affiliateKey])->with('error','Unable To Register User!');
            }
        }
    }
    public function affiliateLogin(Request $request){
        $validator=Validator::make($request->all(),[
            'login_email' => 'required',
            'login_password' => 'required|min:6',
        ],[
            'login_email.required' => 'Email Address is required',
            'login_password.required' => 'Login Password is required',
            'login_password.min' => 'Login Password must have a minimum of 6 characters',
        ]);

        if($validator->fails()){
            $errors=$validator->errors();
            $errors->add('login_errors', 'This is a log in error indicator');
            return redirect()->route('affiliate.registerForm',['affiliateKey' => $request->affiliateKey])->withErrors($errors)->withInput();
        }else{
            $campaignObj=Campaign::where('key',$request->affiliateKey)->first();
            $affiliatesRelated=$campaignObj->affiliate->pluck('user_id');
            if(isset($request->remember)){
                if(Auth::attempt(['email' => $request->login_email,'password' => $request->login_password,'status' => '1','role' => 'affiliate'],true)){
                   if(in_array(Auth::user()->id,$affiliatesRelated->toArray())){
                       return redirect()->route('dashboard');
                   }else{
                       $affiliate=new Affiliate();
                       $affiliate->campaign_id=$campaignObj->id;
                       $affiliate->user_id=Auth::user()->id;
                       $affiliate->key=$this->generateRandomString(16);
                       $affiliate->approve_status=$campaignObj->approval;
                       $affiliate->save();
                       return redirect()->route('dashboard');
                   }
                }else{
                    return redirect()->route('affiliate.registerForm',['affiliateKey' => $request->affiliateKey])->with('error','Invalid User Credentials.Check Email And Password.');
                }
            }else{
                if(Auth::attempt(['email' => $request->login_email,'password' => $request->login_password,'status' => '1','role' => 'affiliate'])){
                    if(in_array(Auth::user()->id,$affiliatesRelated->toArray())){
                        return redirect()->route('dashboard');
                    }else{
                        $affiliate=new Affiliate();
                        $affiliate->campaign_id=$campaignObj->id;
                        $affiliate->user_id=Auth::user()->id;
                        $affiliate->key=$this->generateRandomString(16);
                        $affiliate->approve_status=$campaignObj->approval;
                        $affiliate->save();
                        return redirect()->route('dashboard');
                    }
                }else{
                    return redirect()->route('affiliate.registerForm',['affiliateKey' => $request->affiliateKey])->with('error','Invalid User Credentials.Check Email And Password.');
                }
            }
        }
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
            'name.required' => 'Affiliate name is required',
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
        $affiliates = Affiliate::paginate(25);
        return view('agency.allAffiliate',compact('affiliates'));
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
    public function getReport(Request $request)
    {
        if(isset($request->key) || $request->key != 0){
            $affiliate = Affiliate::where('affiliate_key',$request->key)->first();
            $url = AgentUrl::where('key',$request->urlKey)->first();
            if($affiliate != null && $url != null){
                if(isset($request->dataId) && $request->dataId != 0){
                    $details = AgentUrlDetails::find($request->dataId);
                    $count = $details->count+1;
                    $details->count = $count;
                    $details->update();
                    return response()->json([
                        'success' => true,
                        'message' => 'Affiliate Url Logged',
                    ],200);
                } else {
                    $details = new AgentUrlDetails();
                    $details->url_id = $url->id;
                    $details->affiliate_id = $affiliate->id;
                    $details->type = 1;
                    $details->ip = $request->ip;
                    $details->count = 1;
                    $details->browser = $request->browser;
                    $details->os = $request->os;
                    $details->save();
                    return response()->json([
                        'success' => true,
                        'message' => 'Affiliate Url Logged',
                        'data' => $details->id,
                    ],200);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Agent Not Found'
                ],404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Key Not Found'
            ],400);
        }
    }
    public function thankYou(){
        return view('thankyouRegistration');
    }
    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function detailsAffiliate($id)
    {
        try{
            $affiliate = Affiliate::where('id',$id)->with('user','campaign')->first();
            return view('campaign.affiliate_details',['affiliate' => $affiliate]);
        } catch (\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
