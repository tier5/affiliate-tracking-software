<?php

namespace App\Http\Controllers;

use App\AgentUrl;
use App\AgentUrlDetails;
use App\Campaign;
use App\OrderProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Mail;
use Mockery\Exception;
use \App\Affiliate;
use \App\AffiliateLink;
use \App\Agency;
use \App\BusinessPlan;
use \App\Jobs\AffiliatePlanSync;
use \App\Lead;
use \App\User;
use \App\Visitor;

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
            return redirect()->route('affiliate.registerForm',[$request->affiliateKey])->withErrors($errors)->withInput();
        }else {
            try {
                $user = new User();
                $user->name = $request->registration_username;
                $user->email = $request->registration_email;
                $user->password = bcrypt($request->registration_password);
                $user->status = '1';
                $user->role = 'affiliate';
                if ($user->save()) {
                    $campaignObj = Campaign::where('key', $request->affiliateKey)->first();
                    $affiliate = new Affiliate();
                    $affiliate->campaign_id = $campaignObj->id;
                    $affiliate->user_id = $user->id;
                    $affiliate->key = $this->generateRandomString(16);
                    switch ($campaignObj->approval) {
                        case '1':
                            $affiliate->approve_status = 1;
                            if ($affiliate->save()) {
                                if (Auth::attempt(['email' => $user->email, 'password' => $user->password, 'status' => '1', 'role' => 'affiliate'])) {
                                    return redirect()->route('dashboard');
                                } else {
                                    return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->with('flash', ['message' => 'Unable To Register User!', 'level' => 'danger']);
                                }
                            } else {
                                return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->with('error', 'Unable To Register User!');
                            }
                            break;
                        case '2':
                            $affiliate->approve_status = 2;
                            if ($affiliate->save()) {
                                return redirect()->route('affiliate.thankYou');
                            } else {
                                return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->with('error', 'Unable To Register User!');

                            }
                            break;
                    }
                } else {
                    return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->with('error', 'Unable To Register User!');
                }
            } catch (\Exception $exception)
            {
                return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->withErrors('error', $exception->getMessage());
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
            return redirect()->route('affiliate.registerForm',[$request->affiliateKey])->withErrors($errors)->withInput();
        }else{
            try {
                $campaignObj = Campaign::where('key', $request->affiliateKey)->first();
                $affiliatesRelated = $campaignObj->affiliate->pluck('user_id');
                if (isset($request->remember)) {
                    if (Auth::attempt(['email' => $request->login_email, 'password' => $request->login_password, 'status' => '1', 'role' => 'affiliate'], true)) {
                        if (in_array(Auth::user()->id, $affiliatesRelated->toArray())) {
                            return redirect()->route('dashboard');
                        } else {
                            $affiliate = new Affiliate();
                            $affiliate->campaign_id = $campaignObj->id;
                            $affiliate->user_id = Auth::user()->id;
                            $affiliate->key = $this->generateRandomString(16);
                            $affiliate->approve_status = $campaignObj->approval;
                            $affiliate->save();
                            return redirect()->route('dashboard');
                        }
                    } else {
                        return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->with('error', 'Invalid User Credentials.Check Email And Password.');
                    }
                } else {
                    if (Auth::attempt(['email' => $request->login_email, 'password' => $request->login_password, 'status' => '1', 'role' => 'affiliate'])) {
                        if (in_array(Auth::user()->id, $affiliatesRelated->toArray())) {
                            return redirect()->route('dashboard');
                        } else {
                            $affiliate = new Affiliate();
                            $affiliate->campaign_id = $campaignObj->id;
                            $affiliate->user_id = Auth::user()->id;
                            $affiliate->key = $this->generateRandomString(16);
                            $affiliate->approve_status = $campaignObj->approval;
                            $affiliate->save();
                            return redirect()->route('dashboard');
                        }
                    } else {
                        return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->with('error', 'Invalid User Credentials.Check Email And Password.');
                    }
                }
            }catch(\Exception $exception){
                return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->withErrors('error', $exception->getMessage());
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
            $campaign = Campaign::where('key',$request->urlKey)->first();
            $affiliate = Affiliate::where('key',$request->key)->first();
            if($campaign != null && $affiliate != null){
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
                    //$details->url_id = $campaign->id;
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
            $allTraffic=$affiliate->agentURL;
            $leadsOnly=$allTraffic->where('email','<>','');
            $salesOnly=$allTraffic->where('type','2');
            $orderProducts = OrderProduct::whereIn('log_id', $salesOnly->pluck('id'))->get();
            $commisonsOnly = [];
            $grossCommission = 0;
            foreach ($orderProducts as $key => $order) {
                $product = Product::find($order->product_id);
                if ($product->method == 1) {
                    $myCommision = $product->product_price * ($product->commission / 100);
                    $grossCommission += $myCommision;
                } else {
                    $myCommision = $product->commission;
                    $grossCommission += $myCommision;
                }
                $commisonsOnly[$key]['name'] = $product->name;
                $commisonsOnly[$key]['unit_sold'] = 1;
                $commisonsOnly[$key]['sale_price'] = $product->product_price * 1;
                $commisonsOnly[$key]['commission'] = $myCommision;
            }

            return view('campaign.affiliate_details',[
                'affiliate' => $affiliate,
                'allTraffic' => $allTraffic,
                'leadsOnly' => $leadsOnly,
                'salesOnly' => $salesOnly,
                'commisonsOnly' => $commisonsOnly,
                'grossCommission' => $grossCommission
            ]);
        } catch (\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
    public function getLead(Request $request)
    {
        try{
            $lead = AgentUrlDetails::find($request->dataId);
            if($lead->type != 2){
                $lead->type = 3;
            }
            $lead->email = $request->email;
            //$lead->type = 3;
            $lead->update();
            return response()->json([
                'success' => true,
                'message' => 'Affiliate lead Logged',
            ],200);
        } catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ],500);
        }
    }
    public function sendEmail(Affiliate $affiliate)
    {
        try{
            $affiliateUser = $affiliate->user;
            $campaign= $affiliate->campaign;
            $user=Auth::user();
            Mail::send('email.approve', ['affiliateUser' => $affiliateUser,'campaign' => $campaign, 'user' => $user,'affiliate' => $affiliate], function ($m) use ($affiliateUser) {
                $m->from(env('MAIL_USERNAME'), 'Approval Mail');
                $m->to($affiliateUser->email, $affiliateUser->name)->subject('Approval Mail: Review Velocity');
            });
            return redirect()->route('details.affiliate',[$affiliate->id])->with('success','Mail Sent Successfully!');
        }catch (\Exception $exception) {
            return redirect()->route('details.affiliate',[$affiliate->id])->with('error',$exception->getMessage());
        }
    }
}
