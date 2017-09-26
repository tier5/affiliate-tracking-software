<?php

namespace App\Http\Controllers;

use App\AgentUrl;
use App\AgentUrlDetails;
use App\Campaign;
use App\CustomerRefund;
use App\Jobs\SendRegistrationSms;
use App\OrderProduct;
use App\paidCommission;
use App\PaymentHistory;
use App\Product;
use App\SalesDetail;
use Cartalyst\Stripe\Stripe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
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
use Maatwebsite\Excel\Facades\Excel;

class AffiliateController extends Controller
{
    /**
     * Global Subscription status array
     * @var array
     */
    private $subscriptionStatus = [
        '1' => 'Sale',
        '2' => 'Upgrade',
        '3' => 'Renewal'
    ];

    /**
     * Global Sales Status array
     * @var array
     */
    private $salesStatus = [
        '1' => 'Sale',
        '2' => 'Refunded'
    ];

    /**
     * For Register a affiliate
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function affiliateRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registration_username' => 'required',
            'registration_email' => 'required|email|unique:users,email',
            'registration_password' => 'required|min:6',
            'registration_confirm_password' => 'required|same:registration_password',
        ], [
            'registration_username.required' => 'Username is required',
            'registration_email.required' => 'Email Address is required',
            'registration_email.unique' => 'You are already a registered user with this Email Address.',
            'registration_password.required' => 'Registration Password is required',
            'registration_password.min' => 'Registration Password must have a minimum of 6 characters',
            'registration_confirm_password.required' => 'Confirmation Password is required',
            'registration_confirm_password.same' => 'Registration Password and Confirmation Password does not match',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors->add('registration_errors', 'This is a registration error indicator');
            return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->withErrors($errors)->withInput();
        } else {
            try {
                $user = new User();
                $user->name = $request->registration_username;
                $user->email = $request->registration_email;
                $user->password = bcrypt($request->registration_password);
                $user->status = '1';
                $user->role = 'affiliate';
                if ($user->save()) {
                    $campaignObj = Campaign::where('key', $request->affiliateKey)->first();

                    $url = $campaignObj->sales_url != '' ? $campaignObj->sales_url : $campaignObj->campaign_url;

                    $affiliate = new Affiliate();
                    $affiliate->campaign_id = $campaignObj->id;
                    $affiliate->user_id = $user->id;
                    $affiliate->key = $this->generateRandomString(16);
                    switch ($campaignObj->approval) {
                        case '1':
                            $affiliate->approve_status = 1;
                            if ($affiliate->save()) {
                                $mailNotification = (new SendRegistrationSms($user->name, $user->email, $request->registration_password, $affiliate->key, $url, $campaignObj->name));
                                $this->dispatch($mailNotification);
                                if (Auth::loginUsingId($user->id)) {
                                    return redirect()->route('dashboard');
                                } else {
                                    return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->with('error', 'Unable To Login!');
                                }
                            } else {
                                return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->with('error', 'Unable To Register User!');
                            }
                            break;
                        case '2':
                            $affiliate->approve_status = 2;
                            if ($affiliate->save()) {
                                $mailNotification = (new SendRegistrationSms($user->name, $user->email, $request->registration_password, $affiliate->key, $url, $campaignObj->name));
                                $this->dispatch($mailNotification);
                                return redirect()->route('affiliate.thankYou');
                            } else {
                                return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->with('error', 'Unable To Register User!');

                            }
                            break;
                    }
                } else {
                    return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->with('error', 'Unable To Register User!');
                }
            } catch (\Exception $exception) {
                return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->withErrors('error', $exception->getMessage());
            }
        }
    }

    /**
     * For affiliate login
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function affiliateLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_email' => 'required',
            'login_password' => 'required|min:6',
        ], [
            'login_email.required' => 'Email Address is required',
            'login_password.required' => 'Login Password is required',
            'login_password.min' => 'Login Password must have a minimum of 6 characters',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors->add('login_errors', 'This is a log in error indicator');
            return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->withErrors($errors)->withInput();
        } else {
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
            } catch (\Exception $exception) {
                return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->withErrors('error', $exception->getMessage());
            }
        }
    }

    /**
     * show all affiliate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAffiliate()
    {
        $user = Auth::user();

        $result = [
            'name' => $user->name,
            'email' => $user->email,
            'url' => $user->url
        ];

        return view('agency.dashboard', compact('result'));
    }

    /**
     * Not necessary.This is a previous method
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function links()
    {
        //echo 9999; die;
        //echo $_POST['affilatedID'];
        $affiliateid = $_POST['affilatedID'];
        $affiliateip = $_POST['affilatedIP'];
        $affilatedbrowser = $_POST['affilatedbrowser'];

        //$user = \App\Affiliate::find($userId);
        $affiliateID = Affiliate::where('affiliate_key', $affiliateid)->first();
        echo $affiliateID->affiliate_id;
        $visitorrecord = new Visitor;
        $visitorrecord->affiliate_id = $affiliateID->affiliate_id;
        $visitorrecord->visit_count = 1;
        $visitorrecord->affiliate_ip = $affiliateip;
        $visitorrecord->affiliated_browser = $affilatedbrowser;
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

    /**
     * Add a affiliate to a campaign
     * @param Request $request
     * @return $this
     */
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
        } else {
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
            $result = [
                'name' => $userAuth->name,
                'email' => $userAuth->email,
                'url' => $userAuth->url];
            return view('agency.addAffiliate', compact('result'))->with('message', 'Affiliate created successfully with url  ' . $data['url']);

        }
    }

    /**
     * Show All affiliate(previous)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allAffiliate()
    {
        $affiliates = Affiliate::paginate(25);
        return view('agency.allAffiliate', compact('affiliates'));
    }

    /**
     * Previous method
     */
    public function planSync()
    {
        dispatch(new AffiliatePlanSync());
    }

    /**
     * Get Landing Page Stats (not in Use)
     * @return array
     */
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

    /**
     * Check campaign for JS
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReport(Request $request)
    {
        if (isset($request->key) || $request->key != 0) {
            $campaign = Campaign::where('key', $request->urlKey)->first();
            $affiliate = Affiliate::where('key', $request->key)->first();
            if ($campaign != null && $affiliate != null) {
                if (isset($request->dataId) && $request->dataId != 0) {
                    $details = AgentUrlDetails::find($request->dataId);
                    $count = $details->count + 1;
                    $details->count = $count;
                    $details->update();
                    return response()->json([
                        'success' => true,
                        'message' => 'Affiliate Url Logged',
                    ], 200);
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
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Agent Not Found'
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Key Not Found'
            ], 400);
        }
    }

    /**
     * If affiliate approval was manual for then thank you page come after affiliate registration
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function thankYou()
    {
        return view('thankyouRegistration');
    }

    /**
     * Generate Unique keys
     * @param $length
     * @return string
     */
    function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Show Affiliate Details
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function detailsAffiliate($id)
    {
        try {
            $affiliate = Affiliate::where('id', $id)->with('user', 'campaign')->first();
            $allTraffic = $affiliate->agentURL;
            $leadsOnly = $allTraffic->where('type', '3');
            $salesOnly = $allTraffic->where('type', '2');
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

            return view('campaign.affiliate_details', [
                'affiliate' => $affiliate,
                'allTraffic' => $allTraffic,
                'leadsOnly' => $leadsOnly,
                'salesOnly' => $salesOnly,
                'commisonsOnly' => $commisonsOnly,
                'grossCommission' => $grossCommission
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Check Leads form script
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLead(Request $request)
    {
        try {
            $lead = AgentUrlDetails::find($request->dataId);
            if ($lead->type != 2) {
                $lead->type = 3;
            }
            $lead->email = base64_decode($request->email);
            //$lead->type = 3;
            $lead->update();
            return response()->json([
                'success' => true,
                'message' => 'Affiliate lead Logged',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Send Approval mail
     * @param Affiliate $affiliate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendEmail(Affiliate $affiliate)
    {
        try {
            $affiliateUser = $affiliate->user;
            $campaign = $affiliate->campaign;
            $user = Auth::user();
            Mail::send('email.approve', ['affiliateUser' => $affiliateUser, 'campaign' => $campaign, 'user' => $user, 'affiliate' => $affiliate], function ($m) use ($affiliateUser) {
                $m->from(env('MAIL_USERNAME'), 'Approval Mail');
                $m->to($affiliateUser->email, $affiliateUser->name)->subject('Approval Mail: Review Velocity');
            });
            return redirect()->route('details.affiliate', [$affiliate->id])->with('success', 'Mail Sent Successfully!');
        } catch (\Exception $exception) {
            return redirect()->route('details.affiliate', [$affiliate->id])->with('error', $exception->getMessage());
        }
    }

    /**
     * Show All the affiliate from admin
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function allAffiliateShow()
    {
        try {
            if (Input::get('campaign') > 0) {
                $campaigns = Campaign::where('id', Input::get('campaign'))->orderBy('created_at', 'DESC');
            } else {
                $campaigns = Campaign::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC');
            }
            $campaignsForFilter = Campaign::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
            $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
                ->with(['user', 'campaign'])
                ->orderBy('created_at', 'DESC')->get();
            return view('admin.affiliate', [
                'affiliates' => $affiliates,
                'campaigns' => $campaignsForFilter,
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Show all Sales From admin
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function allSalesShow()
    {
        try {
            $campaignFilter = Input::get('campaign');
            $affiliateFilter = Input::get('affiliate');
            if ($campaignFilter > 0 && $affiliateFilter > 0) {
                $campaigns = Campaign::where('id', $campaignFilter);
                $affiliates = Affiliate::where('campaign_id', $campaignFilter)
                    ->where('approve_status', 1)
                    ->where('user_id', $affiliateFilter)
                    ->orderBy('campaign_id', 'ASC');
            } elseif ($campaignFilter > 0 && $affiliateFilter <= 0) {
                $campaigns = Campaign::where('id', $campaignFilter);
                $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
                    ->where('approve_status', 1)
                    ->orderBy('campaign_id', 'ASC');
            } elseif ($campaignFilter <= 0 && $affiliateFilter > 0) {
                $affiliates = Affiliate::where('user_id', $affiliateFilter)
                    ->where('approve_status', 1)
                    ->orderBy('campaign_id', 'ASC');
                $campaigns = Campaign::whereIn('id', $affiliates->pluck('campaign_id'));
            } else {
                $campaigns = Campaign::where('user_id', Auth::user()->id);
                $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
                    ->where('approve_status', 1)
                    ->orderBy('campaign_id', 'ASC');
            }
            $logs = AgentUrlDetails::whereIn('affiliate_id', $affiliates->pluck('id'))
                ->where('type', 2);
            $orderProducts = OrderProduct::whereIn('log_id', $logs->pluck('id'))
                ->orderBy('created_at', 'DESC')
                ->with('product', 'sales')->get();
            $commisonsOnly = [];
            $grossCommission = 0;
            $refundCommission = 0;
            $refundCount = 0;
            $newCommissionOnly = [];
            foreach ($orderProducts as $keyOrder => $order) {
                foreach ($order->sales as $key => $sales) {
                    $product = Product::find($sales->product_id);
                    $user_log = AgentUrlDetails::find($order->log_id);
                    $logData = Affiliate::find($user_log->affiliate_id);
                    $affiliateData = User::find($logData->user_id);
                    $campaignData = Campaign::find($logData->campaign_id);
//                    if ($order->product->method == 1) {
//                        $myCommision = $order->product->product_price * ($order->product->commission / 100);
//                        $grossCommission += $myCommision;
//                    } else {
//                        $myCommision = $order->product->commission;
//                        $grossCommission += $myCommision;
//                    }
//                    $refund = CustomerRefund::where('log_id',$order->id)->count();
//                    if($refund > 0){
//                        $status = 2;
//                    } else {
//                        $status = 1;
//                    }

                    $commisonsOnly['name'] = $product->name;
                    $commisonsOnly['unit_sold'] = 1;
                    $commisonsOnly['sale_price'] = $product->product_price * 1;
                    $commisonsOnly['commission'] = $sales->commission;
                    $commisonsOnly['email'] = $user_log->email;
                    $commisonsOnly['saleEmail'] = $order->email;
                    $commisonsOnly['id'] = $sales->id;
                    $commisonsOnly['status'] = $order->status;
                    $commisonsOnly['affiliate'] = $affiliateData->name;
                    $commisonsOnly['campaign'] = $campaignData->name;
                    $commisonsOnly['created_at'] = date("F j, Y, g:i a", strtotime($sales->created_at));
                    $commisonsOnly['subscriptionStatus'] = $this->subscriptionStatus[$sales->status];
                    $commisonsOnly['transactionType'] = $this->salesStatus[$sales->type];
                    $commisonsOnly['payment'] = $sales->step_payment_amount;
                    array_push($newCommissionOnly,$commisonsOnly);
                }
                //array_push($newCommissionOnly,$arrayCommission);
            }
            $campaignsDropdown = Campaign::where('user_id', Auth::user()->id)->get();
            $affiliatesDropdown = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
                ->select('user_id')->orderBy('user_id', 'DESC')
                ->groupBy('user_id')->get();
            return view('admin.sales', [
                'sales' => $newCommissionOnly,
                'affiliateDropDown' => $affiliatesDropdown,
                'campaignDropDown' => $campaignsDropdown
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Make Refund
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function salesRefund(Request $request)
    {
        try {
            $sales = SalesDetail::findOrFail($request->id);
            $sales->type = 2;
            $sales->update();
            /*$sale = OrderProduct::findOrFail($request->id);
            $sale->status = 2;
            $sale->update();
            $log = AgentUrlDetails::findOrFail($sale->log_id);
            $affiliate = Affiliate::findOrFail($log->affiliate_id);
            $product = Product::findOrFail($sale->product_id);
            $charge_id = '';
            if ($affiliate->campaign->test_sk != '' && $affiliate->campaign->test_pk != '' && $affiliate->campaign->live_sk != '' && $affiliate->campaign->live_pk != '') {
                if ($affiliate->campaign->stripe_mode == 1) {
                    $key = $affiliate->campaign->test_sk;
                } else {
                    $key = $affiliate->campaign->live_sk;
                }
                $stripe = Stripe::make($key);
                $customers = $stripe->charges()->all();
                foreach ($customers['data'] as $customer){
                    if($customer['receipt_email'] == $sale->email){
                       // if($product->product_price == ($customer['amount'] / 100)){
                            $charge_id = $customer['id'];
                       // }
                    }
                }
                if($charge_id != ''){
                    $refundAction = $stripe->refunds()->create($charge_id);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Customer is not found in stripe! Please Check live or test settings'
                    ], 200);
                }
            } else {
                $refunds = new CustomerRefund();
                $refunds->campaign_id = $affiliate->campaign_id;
                $refunds->log_id = $sale->id;
                $refunds->amount = $product->commission;
                $refunds->save();
            }*/
            return response()->json([
                'success' => true,
                'message' => 'Refunded Successfully'
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 200);
        } catch (ModelNotFoundException $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Affiliate Login from affiliate
     * @param Affiliate $affiliate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminAffiliateLogin(Affiliate $affiliate)
    {
        $affiliateUser = $affiliate->user;
        Session::put('orig_user', Auth::id());
        Auth::loginUsingId($affiliateUser->id);
        return redirect()->route('dashboard');
    }

    /**
     * Check sales from affiliate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function affiliateSales()
    {
        try {
            $campaignFilter = Input::get('campaign');
            if ($campaignFilter > 0) {
                $affiliate = Affiliate::where('user_id', Auth::user()->id)
                    ->where('approve_status', 1)
                    ->where('campaign_id', $campaignFilter)
                    ->orderBy('created_at', 'DESC')->get();
            } else {
                $affiliate = Affiliate::where('user_id', Auth::user()->id)
                    ->where('approve_status', 1)
                    ->orderBy('created_at', 'DESC')->get();
            }
            $sales = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))
                ->where('type', 2)->orderBy('created_at', 'DESC');
            /*
             * Analytics for sold products
             */
            $orderProducts = OrderProduct::whereIn('log_id', $sales->pluck('id'))
                ->with('log', 'sales', 'product')->orderBy('created_at', 'DESC')->get();
            $totalSales = 0;
            $totalSalePrice = 0;
            $grossCommission = 0;
            $refundCount = 0;
            $refundCommission = 0;
            $soldProducts = [];
            $newSoldProduct = [];
            foreach ($orderProducts as $keyOrder => $order) {
                foreach($order->sales as $key => $sale){
                    $product = Product::find($sale->product_id);
                    $log = AgentUrlDetails::find($order->log_id);
                    $affiliateData = Affiliate::find($log->affiliate_id);
                    $campaignData = Campaign::find($affiliateData->campaign_id);
//                    if ($product->method == 1) {
//                        $myCommision = $product->product_price * ($product->commission / 100);
//                        $grossCommission += $myCommision;
//                        if ($order->status == 2) {
//                            $refundCount = $refundCount + 1;
//                            $refundCommission = $refundCommission + $myCommision;
//                        }
//                    } else {
//                        $myCommision = $product->commission;
//                        $grossCommission += $myCommision;
//                        if ($order->status == 2) {
//                            $refundCount = $refundCount + 1;
//                            $refundCommission = $refundCommission + $myCommision;
//                        }
//                    }
                    $refund = CustomerRefund::where('log_id',$order->id)->count();
                    if($refund > 0){
                        $status = 2;
                    } else {
                        $status = 1;
                    }
                    $soldProducts['name'] = $product->name;
                    $soldProducts['unit_sold'] = 1;
                    $soldProducts['total_sale_price'] = $product->product_price;
                    $soldProducts['my_commission'] = $sale->commission;
                    $soldProducts['status'] = $order->status;
                    $soldProducts['email'] = $order->log->email;
                    $soldProducts['saleEmail'] = $order->email;
                    $soldProducts['created_at'] = date("F j, Y, g:i a", strtotime($sale->created_at));
                    $soldProducts['campaign'] = $campaignData->name;
                    $soldProducts['id'] = $sale->id;
                    $soldProducts['subscriptionStatus'] = $this->subscriptionStatus[$sale->status];
                    $soldProducts['transactionType'] = $this->salesStatus[$sale->type];
                    $soldProducts['payment'] = $sale->step_payment_amount;
                    $totalSalePrice += $product->product_price;
                    $totalSales += 1;
                    array_push($newSoldProduct,$soldProducts);
                }
            }
            $affiliateDropDown = Affiliate::where('user_id', Auth::user()->id)
                ->where('approve_status', 1);
            $campaignDropDown = Campaign::whereIn('id', $affiliateDropDown->pluck('campaign_id'))->get();
            return view('affiliate.sales', [
                'sales' => $newSoldProduct,
                'campaignDropDown' => $campaignDropDown
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Check unique affiliate Details
     * @param $affiliate_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function affiliateAllDetails($affiliate_id)
    {
        try {
            $affiliateUser = User::find($affiliate_id);
            $allCampaign = Campaign::where('user_id', Auth::user()->id);
            $filterCampaign = Input::get('campaign');
            if ($filterCampaign > 0) {
                $affiliate = Affiliate::where('user_id', $affiliate_id)
                    ->where('approve_status', 1)
                    ->where('campaign_id', $filterCampaign)
                    ->with('campaign')->get();
                $campaigns = Campaign::where('id', $filterCampaign);
                $paid = paidCommission::where('affiliate_id', $affiliate_id)
                    ->where('campaign_id', $filterCampaign)->get();
            } else {
                $affiliate = Affiliate::where('user_id', $affiliate_id)
                    ->whereIn('campaign_id', $allCampaign->pluck('id'))
                    ->where('approve_status', 1)
                    ->with('campaign')->get();
                $campaigns = Campaign::whereIn('id', $affiliate->pluck('campaign_id'));
                $paid = paidCommission::where('affiliate_id', $affiliate_id)
                    ->where('user_id', Auth::user()->id)->get();
            }
            $affiliateDropDown = Affiliate::where('user_id', $affiliate_id)
                ->whereIn('campaign_id', $allCampaign->pluck('id'))
                ->where('approve_status', 1);
            $campaignDropDown = Campaign::whereIn('id', $affiliateDropDown->pluck('campaign_id'))->get();
            $visitors = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))->orderBy('created_at', 'DESC');
            $leads = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))->where('type', 3)->orderBy('created_at', 'DESC');
            $sales = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))->where('type', 2)->orderBy('created_at', 'DESC');
            $availableProducts = Product::whereIn('campaign_id', $campaigns->pluck('id'))->get();
            $totalSaless = OrderProduct::whereIn('log_id', $sales->pluck('id'))->count();
            $orderObj = OrderProduct::whereIn('log_id', $sales->pluck('id'));
            $newSalesData = SalesDetail::whereIn('sales_id',$orderObj->pluck('id'))->get();
            $newGrossCommission = 0;
            $newRefund = 0;
            $refundCountNew = 0;
            $newSoldProduct = [];
            $newMyCommission = 0;
            foreach ($newSalesData as $key => $value){
                $newGrossCommission = $newGrossCommission + $value->commission;
                if($value->type == 1){
                    $newMyCommission = $newMyCommission + $value->commission;
                } else {
                    $newRefund = $newRefund + $value->commission;
                    $refundCountNew = $refundCountNew + 1;
                }
                $newSoldProduct[$key]['campaign'] = $value->order->log->affiliate->campaign->name;
                $newSoldProduct[$key]['name'] = $value->product->name;
                $newSoldProduct[$key]['product_price'] = $value->product->product_price;
                $newSoldProduct[$key]['unit_sold'] = 1;
                $newSoldProduct[$key]['total_sale_price'] = $value->step_payment_amount;
                $newSoldProduct[$key]['my_commission'] = $value->commission;
                $newSoldProduct[$key]['status'] = $this->subscriptionStatus[$value->status];
                $newSoldProduct[$key]['type'] = $this->salesStatus[$value->type];
                $newSoldProduct[$key]['email'] = $value->order->log->email;
                $newSoldProduct[$key]['saleEmail'] = $value->order->email;
                $newSoldProduct[$key]['date'] = $value->created_at;
                $newSoldProduct[$key]['id'] = $value->id;
            }
            $refunds = CustomerRefund::whereIn('log_id', $orderObj->pluck('id'))->get();
            $totalCommissionRefund = 0;
            foreach ($refunds as $refund) {
                $refundLog = OrderProduct::find($refund->log_id);
                $product = $refundLog->product;
                if ($product->method == 1) {
                    $commission = $refund->amount * ($product->commission / 100);
                } else {
                    $commission = $refund->amount * ($product->commission / $product->product_price);
                }
                $totalCommissionRefund += $commission;
            }
            /*
             * Analytics for sold products
             */
            $paidCommission = 0;
            if (count($paid) > 0) {
                foreach ($paid as $pay) {
                    $paidCommission = $pay->paid_commission;
                }
            }
            $orderProducts = OrderProduct::whereIn('log_id', $sales->pluck('id'))->with('log')->orderBy('created_at', 'DESC')->get();
            $totalSales = 0;
            $totalSalePrice = 0;
            $grossCommission = 0;
            $refundCount = 0;
            $refundCommission = 0;
            $soldProducts = [];
            $netCommission = 0;
            foreach ($orderProducts as $key => $order) {
                $product = Product::find($order->product_id);
                $user_log = AgentUrlDetails::find($order->log_id);
                $logData = Affiliate::find($user_log->affiliate_id);
                $campaignData = Campaign::find($logData->campaign_id);
                if ($product->method == 1) {
                    $myCommision = $product->product_price * ($product->commission / 100);
                    $grossCommission += $myCommision;
                    if ($order->status == 2) {
                        $refundCount = $refundCount + 1;
                        $refundCommission = $refundCommission + $myCommision;
                    }
                } else {
                    $myCommision = $product->commission;
                    $grossCommission += $myCommision;
                    if ($order->status == 2) {
                        $refundCount = $refundCount + 1;
                        $refundCommission = $refundCommission + $myCommision;
                    }
                }
                $refund = CustomerRefund::where('log_id',$order->id)->count();
                if($refund > 0){
                    $status = 2;
                } else {
                    $status = 1;
                }
                $soldProducts[$key]['campaign'] = $campaignData->name;
                $soldProducts[$key]['name'] = $product->name;
                $soldProducts[$key]['unit_sold'] = 1;
                $soldProducts[$key]['total_sale_price'] = $product->product_price * 1;
                $soldProducts[$key]['my_commission'] = $myCommision;
                $soldProducts[$key]['status'] = $order->status;
                $soldProducts[$key]['email'] = $order->log->email;
                $soldProducts[$key]['saleEmail'] = $order->email;
                $soldProducts[$key]['date'] = $order->created_at;
                $soldProducts[$key]['id'] = $order->id;
                $totalSalePrice += $soldProducts[$key]['total_sale_price'];
                $totalSales += 1;
            }
            $netCommission = $newGrossCommission - $newRefund;
            return view('admin.add_affiliate_details', [
                'affiliate' => $affiliate,
                'campaigns' => $campaigns->count(),
                'visitors' => $visitors->count(),
                'leads' => $leads->count(),
                'sales' => $sales->count(),
                'totalSales' => $totalSaless,
                'total_sale_price' => $totalSalePrice,
                'gross_commission' => $newGrossCommission,
                'refundCommission' => $newRefund,
                'refundCount' => $refundCountNew,
                'available_products' => $availableProducts,
                'sold_products' => $newSoldProduct,
                'campaignDropDown' => $campaignDropDown,
                'paidCommission' => $paidCommission,
                'netCommission' => $netCommission,
                'affiliateUser' => $affiliateUser,
                'allTraffic' => $visitors->get(),
                'leadsOnly' => $leads->get(),
                'salesOnly' => $sales->get(),
                'commisonsOnly' => $newSoldProduct,
                'newSalesCount' => count($newSalesData)
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Pay Commission
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function payCommission(Request $request)
    {
        try {
            $affiliate = Affiliate::where('user_id', $request->affiliate)->firstorFail();
            $campaign = Campaign::findOrFail($request->campaign);
            $paidCommission = paidCommission::where('affiliate_id', $affiliate->user_id)
                ->where('user_id', $campaign->user_id)
                ->where('campaign_id', $campaign->id)->first();
            if ($paidCommission == '') {
                $paidCommission = new paidCommission();
                $paidCommission->affiliate_id = $affiliate->user_id;
                $paidCommission->user_id = $campaign->user_id;
                $paidCommission->paid_commission = $request->commission;
                $paidCommission->campaign_id = $campaign->id;
                $paidCommission->save();
            } else {
                $previousCommission = $paidCommission->paid_commission + $request->commission;
                $paidCommission->paid_commission = $previousCommission;
                $paidCommission->update();
            }

            $history = new PaymentHistory();
            $history->commission_id = $paidCommission->id;
            $history->amount = $request->commission;
            $history->save();

            return response()->json([
                'success' => true,
                'message' => 'Commission paid successfully'
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * View Details
     * @param $user_type
     * @param $user_id
     * @param $link_type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewDetailsLink($user_type, $user_id, $link_type)
    {
        try {
            if ($user_type == 'affiliate') {
                $filterCampaign = Input::get('campaign');
                if ($filterCampaign > 0) {
                    $affiliate = Affiliate::where('user_id', Auth::user()->id)
                        ->where('approve_status', 1)
                        ->where('campaign_id', $filterCampaign)->get();
                    $campaigns = Campaign::where('id', $filterCampaign);
                } else {
                    $affiliate = Affiliate::where('user_id', Auth::user()->id)->where('approve_status', 1)->get();
                    $campaigns = Campaign::whereIn('id', $affiliate->pluck('campaign_id'));
                }
                $affiliateDropDown = Affiliate::where('user_id', Auth::user()->id)
                    ->where('approve_status', 1);
                $campaignsDropdown = Campaign::whereIn('id', $affiliateDropDown->pluck('campaign_id'))->get();
                switch ($link_type) {
                    case "visitor":
                        $allTraffic = $this->getAgentURLFromAffiliates($affiliate);
                        break;
                    case "sale":
                        $allTraffic = $this->getAgentURLFromAffiliates($affiliate, '2');
                        break;
                    case "refund":
                        $allTraffic = $this->getAgentURLFromAffiliates($affiliate, '4');
                        break;
                }
                return view('admin.admin_details', [
                    'userType' => $user_type,
                    'linkName' => $link_type,
                    'allTraffic' => $allTraffic,
                    'campaignsDropdown' => $campaignsDropdown
                ]);
            } elseif ($user_type == 'admin') {

                if (Input::get('campaign_id') == 0 && Input::get('affiliate_id') == 0) {
                    $campaigns = Campaign::where('user_id', Auth::user()->id)->with('affiliate');
                    $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'));
                    $campaignsDropdown = Campaign::where('user_id', Auth::user()->id)->get();
                    $affiliatesDropdown = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
                        ->select('user_id')->orderBy('user_id', 'DESC')
                        ->groupBy('user_id')->get();
                    switch ($link_type) {
                        case "visitor":
                            $allTraffic = $this->getAgentURLAdminDefault($user_id);
                            break;
                        case "leads":
                            $allTraffic = $this->getAgentURLAdminDefault($user_id, '3');
                            break;
                        case "sales":
                            $allTraffic = $this->getAgentURLAdminDefault($user_id, '2');
                            break;
                        case "refund":
                            $allTraffic = $this->getAgentURLAdminDefault($user_id, '4');
                            break;
                    }
                } else if (Input::get('campaign_id') > 0 && Input::get('affiliate_id') <= 0) {
                    $campaigns = Campaign::where('id', Input::get('campaign_id'))
                        ->where('user_id', Auth::user()->id);
                    $campaignsDropdown = Campaign::where('user_id', Auth::user()->id)->get();
                    if ($campaigns->count()) {
                        $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'));
                        $allTraffic = [];
                        $affiliatesDropdown = Affiliate::where('campaign_id', $campaigns->first()->id)->get();
                    }
                    switch ($link_type) {
                        case "visitor":
                            $allTraffic = $this->getAgentURLFromAffiliates($affiliates->get());
                            break;
                        case "leads":
                            $allTraffic = $this->getAgentURLFromAffiliates($affiliates->get(), '3');
                            break;
                        case "sales":
                            $allTraffic = $this->getAgentURLFromAffiliates($affiliates->get(), '2');
                            break;
                        case "refund":
                            $allTraffic = $this->getAgentURLFromAffiliates($affiliates->get(), '4');
                            break;
                    }
                } else if (Input::get('campaign_id') <= 0 && Input::get('affiliate_id') > 0) {
                    $affiliates = Affiliate::where('user_id', Input::get('affiliate_id'))->with('campaign');
                    if ($affiliates->first()) {
                        $campaigns = Affiliate::where('user_id', Input::get('affiliate_id'));
                    }
                    $allTraffic = [];
                    $campaignsDropdown = Campaign::whereIn('id', $campaigns->pluck('campaign_id'))->get();
                    $affiliatesDropdown = Affiliate::whereIn('campaign_id', Campaign::where('user_id', Auth::user()->id)
                        ->pluck('id'))->select('user_id')->orderBy('user_id', 'DESC')
                        ->groupBy('user_id')->get();
                    switch ($link_type) {
                        case "visitor":
                            $allTraffic = $this->getAgentURLFromAffiliates($affiliates->get());
                            break;
                        case "leads":
                            $allTraffic = $this->getAgentURLFromAffiliates($affiliates->get(), '3');
                            break;
                        case "sales":
                            $allTraffic = $this->getAgentURLFromAffiliates($affiliates->get(), '2');
                            break;
                        case "refund":
                            $allTraffic = $this->getAgentURLFromAffiliates($affiliates->get(), '4');
                            break;
                    }
                } else if (Input::get('campaign_id') > 0 && Input::get('affiliate_id') > 0) {
                    $campaigns = Campaign::where('id', Input::get('campaign_id'))
                        ->where('user_id', Auth::user()->id);
                    if ($campaigns->count()) {
                        $affiliates = Affiliate::where('user_id', Input::get('affiliate_id'))
                            ->where('campaign_id', Input::get('campaign_id'));
                        $allTraffic = [];
                        $affiliatesDropdown = Affiliate::where('campaign_id', $campaigns->first()->id)->get();
                    }
                    $campaignsDropdown = Campaign::where('user_id', Auth::user()->id)->get();
                    switch ($link_type) {
                        case "visitor":
                            $allTraffic = $this->getAgentURLFromAffiliates($affiliates->get());
                            break;
                        case "leads":
                            $allTraffic = $this->getAgentURLFromAffiliates($affiliates->get(), '3');
                            break;
                        case "sales":
                            $allTraffic = $this->getAgentURLFromAffiliates($affiliates->get(), '2');
                            break;
                        case "refund":
                            $allTraffic = $this->getAgentURLFromAffiliates($affiliates->get(), '4');
                            break;
                    }
                }
                return view('admin.admin_details', [
                    'userType' => $user_type,
                    'linkName' => $link_type,
                    'allTraffic' => $allTraffic,
                    'affiliatesDropdown' => $affiliatesDropdown,
                    'campaignsDropdown' => $campaignsDropdown
                ]);
            } else {
                return redirect()->back()->with('error', 'please select a valid user type');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     *
     * @param $user_id
     * @param null $type
     * @return array
     */
    public function getAgentURLAdminDefault($user_id, $type = null)
    {
        $array = [];
        $campaigns = Campaign::where('user_id', $user_id)->get();
        foreach ($campaigns as $campaign) {
            $affiliates = $campaign->affiliate;
            foreach ($affiliates as $affiliate) {
                if (is_null($type)) {
                    $agentURLs = $affiliate->agentURL;
                    foreach ($agentURLs as $agentURL) {
                        array_push($array, $agentURL);
                    }
                } else {

                    switch ($type) {
                        case '2':
                        case '3':
                            $agentURLs = $affiliate->agentURL->where('type', $type);
                            foreach ($agentURLs as $agentURL) {
                                array_push($array, $agentURL);
                            }
                            break;
                        case '4':
                            $agentURLs = $affiliate->agentURL;
                            foreach ($agentURLs as $agentURL) {
                                $refunds = OrderProduct::where('log_id', $agentURL->id)->where('status', '2')->get();
                                foreach ($refunds as $refund) {
                                    array_push($array, $refund->log);
                                }
                            }
                            break;
                    }
                }
            }
        }
        return $array;
    }

    /**
     * @param null $affiliates
     * @param null $type
     * @return array
     */
    public function getAgentURLFromAffiliates($affiliates = null, $type = null)
    {
        $array = [];
        foreach ($affiliates as $affiliate) {
            if (is_null($type)) {
                $agentURLs = $affiliate->agentURL;
                foreach ($agentURLs as $agentURL) {
                    array_push($array, $agentURL);
                }
            } else {
                switch ($type) {
                    case '2':
                    case '3':
                        $agentURLs = $affiliate->agentURL->where('type', $type);
                        foreach ($agentURLs as $agentURL) {
                            array_push($array, $agentURL);
                        }
                        break;

                    case '4':
                        $agentURLs = $affiliate->agentURL;
                        foreach ($agentURLs as $agentURL) {
                            $refunds = OrderProduct::where('log_id', $agentURL->id)->where('status', '2')->get();
                            foreach ($refunds as $refund) {
                                array_push($array, $refund->log);
                            }
                        }
                        break;
                }
            }
        }
        return $array;
    }

    /**
     * Admin sales export
     * @return string
     */
    public function exportSalesAdmin()
    {
        try {
            $campaignFilter = Input::get('campaign');
            $affiliateFilter = Input::get('affiliate');
            if ($campaignFilter > 0 && $affiliateFilter > 0) {
                $campaigns = Campaign::where('id', $campaignFilter);
                $affiliates = Affiliate::where('campaign_id', $campaignFilter)
                    ->where('approve_status', 1)
                    ->where('user_id', $affiliateFilter)
                    ->orderBy('campaign_id', 'ASC');
            } elseif ($campaignFilter > 0 && $affiliateFilter <= 0) {
                $campaigns = Campaign::where('id', $campaignFilter);
                $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
                    ->where('approve_status', 1)
                    ->orderBy('campaign_id', 'ASC');
            } elseif ($campaignFilter <= 0 && $affiliateFilter > 0) {
                $affiliates = Affiliate::where('user_id', $affiliateFilter)
                    ->where('approve_status', 1)
                    ->orderBy('campaign_id', 'ASC');
                $campaigns = Campaign::whereIn('id', $affiliates->pluck('campaign_id'));
            } else {
                $campaigns = Campaign::where('user_id', Auth::user()->id);
                $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
                    ->where('approve_status', 1)
                    ->orderBy('campaign_id', 'ASC');
            }
            $logs = AgentUrlDetails::whereIn('affiliate_id', $affiliates->pluck('id'))
                ->where('type', 2);
            $orderProducts = OrderProduct::whereIn('log_id', $logs->pluck('id'))
                ->orderBy('created_at', 'DESC')
                ->with('product')->get();
            $commisonsOnly = [];
            $grossCommission = 0;
            $refundCommission = 0;
            $refundCount = 0;
            $newCommissionOnly = [];
            foreach ($orderProducts as $keyOrder => $order) {
                foreach ($order->sales as $key => $sales){
                    $product = Product::find($sales->product_id);
                    $user_log = AgentUrlDetails::find($order->log_id);
                    $logData = Affiliate::find($user_log->affiliate_id);
                    $affiliateData = User::find($logData->user_id);
                    $campaignData = Campaign::find($logData->campaign_id);
                    $subscriptionStatus = $this->subscriptionStatus[$sales->status];
                    $transactionType = $this->salesStatus[$sales->type];
                    $commisonsOnly['Campaign'] = $campaignData->name;
                    $commisonsOnly['Affiliate'] = $affiliateData->name;
                    $commisonsOnly['Email'] = $order->email;
                    $commisonsOnly['Product Name'] = $product->name;
                    $commisonsOnly['Product Price'] = '$'.$product->product_price * 1;
                    $commisonsOnly['Sale Price'] = '$'.$sales->step_payment_amount;
                    $commisonsOnly['Commission'] = '$'.$sales->commission;
                    $commisonsOnly['Date'] = date("F j, Y, g:i a", strtotime($sales->created_at));
                    $commisonsOnly['Status'] = $transactionType == 'Refunded'? 'Refunded' : $subscriptionStatus;
                    array_push($newCommissionOnly,$commisonsOnly);
                }
            }
            /*foreach ($orderProducts as $key => $order) {
                $user_log = AgentUrlDetails::find($order->log_id);
                $logData = Affiliate::find($user_log->affiliate_id);
                $affiliateData = User::find($logData->user_id);
                $campaignData = Campaign::find($logData->campaign_id);
                if ($order->product->method == 1) {
                    $myCommision = $order->product->product_price * ($order->product->commission / 100);
                    $grossCommission += $myCommision;
                } else {
                    $myCommision = $order->product->commission;
                    $grossCommission += $myCommision;
                }
                $commisonsOnly[$key]['Campaign'] = $campaignData->name;
                $commisonsOnly[$key]['Affiliate'] = $affiliateData->name;
                $commisonsOnly[$key]['Email'] = ($order->email != '') ? $order->email : $user_log->email;
                $commisonsOnly[$key]['Product Name'] = $order->product->name;
                $commisonsOnly[$key]['Price'] = '$' . $order->product->product_price * 1;
                $commisonsOnly[$key]['Commission'] = '$' . $myCommision;
                $commisonsOnly[$key]['Date'] = date("F j, Y, g:i a", strtotime($order->created_at));
                $commisonsOnly[$key]['Status'] = ($order->status == 2) ? 'Refunded' : 'Sales';
            }*/
            Excel::create('Sales_' . date('m_d_Y'), function ($excel) use ($newCommissionOnly) {
                $excel->sheet('Sales sheet', function ($sheet) use ($newCommissionOnly) {
                    $sheet->fromArray($newCommissionOnly, null, 'A1', true);
                });
            })->download('xls');
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Affiliate Sales Track;
     * @return string
     */
    public function exportSalesAffiliate()
    {
        try {
            $campaignFilter = Input::get('campaign');
            if ($campaignFilter > 0) {
                $affiliate = Affiliate::where('user_id', Auth::user()->id)
                    ->where('approve_status', 1)
                    ->where('campaign_id', $campaignFilter)->get();
            } else {
                $affiliate = Affiliate::where('user_id', Auth::user()->id)
                    ->where('approve_status', 1)->get();
            }
            $sales = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))
                ->where('type', 2)->orderBy('created_at', 'DESC');
            /*
             * Analytics for sold products
             */
            $orderProducts = OrderProduct::whereIn('log_id', $sales->pluck('id'))
                ->with('log')->orderBy('created_at', 'DESC')->get();
            $totalSales = 0;
            $totalSalePrice = 0;
            $grossCommission = 0;
            $refundCount = 0;
            $refundCommission = 0;
            $soldProducts = [];
            $newCommissionOnly = [];
            foreach ($orderProducts as $keyOrder => $order) {
                foreach ($order->sales as $key => $sales){
                    $product = Product::find($sales->product_id);
                    $user_log = AgentUrlDetails::find($order->log_id);
                    $logData = Affiliate::find($user_log->affiliate_id);
                    $affiliateData = User::find($logData->user_id);
                    $campaignData = Campaign::find($logData->campaign_id);
                    $subscriptionStatus = $this->subscriptionStatus[$sales->status];
                    $transactionType = $this->salesStatus[$sales->type];
                    $soldProducts['Campaign'] = $campaignData->name;
                    $soldProducts['Affiliate'] = $affiliateData->name;
                    $soldProducts['Email'] = $order->email;
                    $soldProducts['Product Name'] = $product->name;
                    $soldProducts['Product Price'] = '$'.$product->product_price * 1;
                    $soldProducts['Sale Price'] = '$'.$sales->step_payment_amount;
                    $soldProducts['Commission'] = '$'.$sales->commission;
                    $soldProducts['Date'] = date("F j, Y, g:i a", strtotime($sales->created_at));
                    $soldProducts['Status'] = $transactionType == 'Refunded'? 'Refunded' : $subscriptionStatus;
                    array_push($newCommissionOnly,$soldProducts);
                }
            }
            /*foreach ($orderProducts as $key => $order) {
                $product = Product::find($order->product_id);
                $log = AgentUrlDetails::find($order->log_id);
                $affiliateData = Affiliate::find($log->affiliate_id);
                $campaignData = Campaign::find($affiliateData->campaign_id);
                if ($product->method == 1) {
                    $myCommision = $product->product_price * ($product->commission / 100);
                    $grossCommission += $myCommision;
                    if ($order->status == 2) {
                        $refundCount = $refundCount + 1;
                        $refundCommission = $refundCommission + $myCommision;
                    }
                } else {
                    $myCommision = $product->commission;
                    $grossCommission += $myCommision;
                    if ($order->status == 2) {
                        $refundCount = $refundCount + 1;
                        $refundCommission = $refundCommission + $myCommision;
                    }
                }
                $soldProducts[$key]['Campaign'] = $campaignData->name;
                $soldProducts[$key]['Email'] = ($order->email != '') ? $order->email : $order->log->email;
                $soldProducts[$key]['Product name'] = $product->name;
                $soldProducts[$key]['Price'] = '$' . $product->product_price;
                $soldProducts[$key]['Commission'] = '$' . $myCommision;
                $soldProducts[$key]['Date'] = date("F j, Y, g:i a", strtotime($order->created_at));
                $soldProducts[$key]['Status'] = ($order->status == 2) ? 'Refunded' : 'Sales';
            }*/
            Excel::create('Sales_' . date('m_d_Y'), function ($excel) use ($newCommissionOnly) {
                $excel->sheet('Sales sheet', function ($sheet) use ($newCommissionOnly) {
                    $sheet->fromArray($newCommissionOnly, null, 'A1', true);
                });
            })->download('xls');
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Edit Affiliate
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAffiliate(Request $request)
    {
        try {
            $checkEmail = User::where('email', $request->email)
                ->where('id', '!=', $request->id)->first();
            if ($checkEmail == '') {
                $user = User::find($request->id);
                $user->email = $request->email;
                $user->name = $request->name;
                $user->update();
                return response()->json([
                    'success' => true,
                    'message' => 'Affiliate Updated'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already Exists'
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
