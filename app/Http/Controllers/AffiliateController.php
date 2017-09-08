<?php

namespace App\Http\Controllers;

use App\AgentUrl;
use App\AgentUrlDetails;
use App\Campaign;
use App\OrderProduct;
use App\paidCommission;
use App\PaymentHistory;
use App\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
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


class AffiliateController extends Controller
{
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
            } catch (\Exception $exception) {
                return redirect()->route('affiliate.registerForm', [$request->affiliateKey])->withErrors('error', $exception->getMessage());
            }
        }
    }

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

    public function allAffiliate()
    {
        $affiliates = Affiliate::paginate(25);
        return view('agency.allAffiliate', compact('affiliates'));
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

    public function thankYou()
    {
        return view('thankyouRegistration');
    }

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

    public function getLead(Request $request)
    {
        try {
            $lead = AgentUrlDetails::find($request->dataId);
            if ($lead->type != 2) {
                $lead->type = 3;
            }
            $lead->email = $request->email;
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

    public function allAffiliateShow()
    {
        try {
            if (Input::get('campaign') > 0) {
                $campaigns = Campaign::where('id', Input::get('campaign'));
            } else {
                $campaigns = Campaign::where('user_id', Auth::user()->id);
            }
            $campaignsForFilter = Campaign::where('user_id', Auth::user()->id)->get();
            $affiliates = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
                ->where('approve_status', 1)
                ->with(['user', 'campaign'])
                ->orderBy('campaign_id', 'ASC')->get();
            return view('admin.affiliate', [
                'affiliates' => $affiliates,
                'campaigns' => $campaignsForFilter,
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

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
                ->with('product')->get();
            $commisonsOnly = [];
            $grossCommission = 0;
            $refundCommission = 0;
            $refundCount = 0;
            foreach ($orderProducts as $key => $order) {
                $user_log = AgentUrlDetails::find($order->log_id);
                if ($order->product->method == 1) {
                    $myCommision = $order->product->product_price * ($order->product->commission / 100);
                    $grossCommission += $myCommision;
                } else {
                    $myCommision = $order->product->commission;
                    $grossCommission += $myCommision;
                }
                $commisonsOnly[$key]['name'] = $order->product->name;
                $commisonsOnly[$key]['unit_sold'] = 1;
                $commisonsOnly[$key]['sale_price'] = $order->product->product_price * 1;
                $commisonsOnly[$key]['commission'] = $myCommision;
                $commisonsOnly[$key]['email'] = $user_log->email;
                $commisonsOnly[$key]['saleEmail'] = $order->email;
                $commisonsOnly[$key]['id'] = $order->id;
                $commisonsOnly[$key]['status'] = $order->status;
            }
            $campaignsDropdown = Campaign::where('user_id', Auth::user()->id)->get();
            $affiliatesDropdown = Affiliate::whereIn('campaign_id', $campaigns->pluck('id'))
                ->select('user_id')->orderBy('user_id', 'DESC')
                ->groupBy('user_id')->get();
            return view('admin.sales', [
                'sales' => $commisonsOnly,
                'affiliateDropDown' => $affiliatesDropdown,
                'campaignDropDown' => $campaignsDropdown
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function salesRefund(Request $request)
    {
        try {
            $sale = OrderProduct::find($request->id);
            $sale->status = 2;
            $sale->update();
            return response()->json([
                'success' => true,
                'message' => 'Refund Successful'
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    public function adminAffiliateLogin(Affiliate $affiliate)
    {
        $affiliateUser = $affiliate->user;
        Session::put('orig_user', Auth::id());
        Auth::loginUsingId($affiliateUser->id);
        return redirect()->route('dashboard');
    }

    public function affiliateSales()
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
            $sales = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))->where('type', 2);
            /*
             * Analytics for sold products
             */
            $orderProducts = OrderProduct::whereIn('log_id', $sales->pluck('id'))->with('log')->get();
            $totalSales = 0;
            $totalSalePrice = 0;
            $grossCommission = 0;
            $refundCount = 0;
            $refundCommission = 0;
            $soldProducts = [];
            foreach ($orderProducts as $key => $order) {
                $product = Product::find($order->product_id);
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
                $soldProducts[$key]['name'] = $product->name;
                $soldProducts[$key]['unit_sold'] = 1;
                $soldProducts[$key]['total_sale_price'] = $product->product_price;
                $soldProducts[$key]['my_commission'] = $myCommision;
                $soldProducts[$key]['status'] = $order->status;
                $soldProducts[$key]['email'] = $order->log->email;
                $soldProducts[$key]['saleEmail'] = $order->email;
                $totalSalePrice += $soldProducts[$key]['total_sale_price'];
                $totalSales += 1;
            }
            $affiliateDropDown = Affiliate::where('user_id', Auth::user()->id)
                ->where('approve_status', 1);
            $campaignDropDown = Campaign::whereIn('id', $affiliateDropDown->pluck('campaign_id'))->get();
            return view('affiliate.sales', [
                'sales' => $soldProducts,
                'campaignDropDown' => $campaignDropDown
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

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
            $visitors = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))->orderBy('created_at','DESC');
            $leads = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))->where('type', 3)->orderBy('created_at','DESC');
            $sales = AgentUrlDetails::whereIn('affiliate_id', $affiliate->pluck('id'))->where('type', 2)->orderBy('created_at','DESC');
            $availableProducts = Product::whereIn('campaign_id', $campaigns->pluck('id'))->get();
            $totalSaless = OrderProduct::whereIn('log_id', $sales->pluck('id'))->count();
            /*
             * Analytics for sold products
             */
            $paidCommission = 0;
            if (count($paid) > 0) {
                foreach ($paid as $pay) {
                    $paidCommission = $pay->paid_commission;
                }
            }
            $orderProducts = OrderProduct::whereIn('log_id', $sales->pluck('id'))->with('log')->orderBy('created_at','DESC')->get();
            $totalSales = 0;
            $totalSalePrice = 0;
            $grossCommission = 0;
            $refundCount = 0;
            $refundCommission = 0;
            $soldProducts = [];
            $netCommission = 0;
            foreach ($orderProducts as $key => $order) {
                $product = Product::find($order->product_id);
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
            $netCommission = $grossCommission - $refundCommission;
            return view('admin.add_affiliate_details', [
                'affiliate' => $affiliate,
                'campaigns' => $campaigns->count(),
                'visitors' => $visitors->count(),
                'leads' => $leads->count(),
                'sales' => $sales->count(),
                'totalSales' => $totalSaless,
                'total_sale_price' => $totalSalePrice,
                'gross_commission' => $grossCommission,
                'refundCommission' => $refundCommission,
                'refundCount' => $refundCount,
                'available_products' => $availableProducts,
                'sold_products' => $soldProducts,
                'campaignDropDown' => $campaignDropDown,
                'paidCommission' => $paidCommission,
                'netCommission' => $netCommission,
                'affiliateUser' => $affiliateUser,
                'allTraffic' => $visitors->get(),
                'leadsOnly' => $leads->get(),
                'salesOnly' => $sales->get(),
                'commisonsOnly' => $soldProducts,
            ]);
        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function payCommission(Request $request)
    {
        try {
            $affiliate = Affiliate::where('user_id', $request->affiliate)->firstorFail();
            $campaign = Campaign::findOrFail($request->campaign);
            $paidCommission = paidCommission::where('affiliate_id', $affiliate->user_id)
                ->where('campaign_id', $campaign->id)->first();
            if ($paidCommission == '') {
                $paidCommission = new paidCommission();
                $paidCommission->affiliate_id = $affiliate->user_id;
                $paidCommission->user_id = $campaign->user_id;
                $paidCommission->campaign_id = $campaign->id;
                $paidCommission->paid_commission = $request->commission;
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
                'message' => 'Commission paid successful'
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

    public function viewDetailsLink($user_type, $user_id, $link_type)
    {
        try {
            if ($user_type == 'affiliate') {
                switch ($link_type) {
                    case "visitor":
                        $allTraffic = $this->getURLFromAffiliates($user_id);
                        break;
                    case "sale":
                        $allTraffic = $this->getURLFromAffiliates($user_id, '2');
                        break;
                    case "refund":
                        $allTraffic = $this->getURLFromAffiliates($user_id, '4');
                        break;
                }
                return view('admin.admin_details', [
                    'linkName' => $link_type,
                    'allTraffic' => $allTraffic
                ]);
            } elseif ($user_type == 'admin') {

                switch ($link_type) {
                    case "visitor":
                        $allTraffic = $this->getURLFromCampaign($user_id);
                        break;
                    case "leads":
                        $allTraffic = $this->getURLFromCampaign($user_id, '3');
                        break;
                    case "sales":
                        $allTraffic = $this->getURLFromCampaign($user_id, '2');
                        break;
                    case "refund":
                        $allTraffic = $this->getURLFromCampaign($user_id, '4');
                        break;
                }
                return view('admin.admin_details', [
                    'linkName' => $link_type,
                    'allTraffic' => $allTraffic
                ]);
            } else {
                return redirect()->back()->with('error', 'please select a valid user type');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function getURLFromCampaign($user_id, $type = null)
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

    public function getURLFromAffiliates($user_id, $type = null)
    {
        $array = [];
        $affiliates = Affiliate::where('user_id', $user_id)->get();
        foreach ($affiliates as $affiliate) {
            if (is_null($type)) {
                $agentURLs = $affiliate->agentURL;
                foreach ($agentURLs as $agentURL) {
                    array_push($array, $agentURL);
                }
            } else {
                switch ($type) {
                    case '2':
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
}