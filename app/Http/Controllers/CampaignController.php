<?php

namespace App\Http\Controllers;

use App\Affiliate;
use App\Campaign;
use App\Jobs\SendRegistrationSms;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CampaignController extends Controller
{
    public function getCampaign()
    {
        $campaign = Campaign::where('user_id',Auth()->user()->id)->paginate(25);
        return view('campaign.campaign',['campaigns' => $campaign]);
    }
    public function createCampaign(Request $request)
    {
        try{
            if($request->approve == 'on'){
                $approval = 1;
            } else {
                $approval = 2;
            }
            $url =  parse_url($request->url);
            $campaign = new Campaign();
            $campaign->name = $request->name;
            $campaign->url = $url['host'];
            $campaign->campaign_url = $request->url;
            $campaign->sales_url = $request->sales_url;
            $campaign->user_id = $request->user_id;
            $campaign->approval = $approval;
            $campaign->key = $request->key;
            $campaign->product_type=$request->product_type;
            $campaign->test_pk = $request->test_pk;
            $campaign->test_sk = $request->test_sk;
            $campaign->live_pk = $request->live_pk;
            $campaign->live_sk = $request->live_sk;
            $campaign->stripe_mode = 1;
            $campaign->save();
            return response()->json([
                'success' => true,
                'message' => 'Campaign Created Successfully'
            ],200);
        } catch (\Exception $exception){
            return response()->json([
                'status' => $exception->getCode(),
                'success' => false,
                'message' => $exception->getMessage()
            ],200);
        }
    }
    public function deleteCampaign(Request $request)
    {
        try{
            $campaign = Campaign::find($request->id);
            $campaign->delete();
            return response()->json([
                'success' => true,
                'message' => 'Campaign Deleted Successfully'
            ],200);
        } catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ],500);
        }
    }
    public function editCampaign(Request $request)
    {
        try{
            $campaign = Campaign::find($request->id);
            $campaign->name = $request->name;
            $campaign->sales_url = $request->sales_url;
            $campaign->campaign_url = $request->campaign_url;
            $campaign->approval = $request->status;
            $campaign->test_sk = $request->test_sk;
            $campaign->test_pk = $request->test_pk;
            $campaign->live_sk = $request->live_sk;
            $campaign->live_pk = $request->live_pk;
            $campaign->update();
            return response()->json([
                'success' => true,
                'message' => 'Campaign Edited Successfully'
            ],200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ],500);
        }
    }
    public function detailsCampaign($key)
    {
        try{
            $campaign = Campaign::where('key',$key)->with('affiliate')->first();
            foreach ($campaign->affiliate as $key => $affiliate){
                $user = User::find($affiliate->user_id);
                $affiliate->email = $user->email;
                $affiliate->name = $user->name;
            }
            return view('campaign.campaign_details',['campaigns' => $campaign]);
        } catch (\Exception $exception){
            return redirect()->back()->withErrors(['error',$exception->getMessage()]);
        }
    }

    public function affiliateRegistrationForm($affiliateKey){
        $campaign = Campaign::where('key',$affiliateKey)->with('user')->first();
        return view('affiliate.registration',['campaign' => $campaign]);
    }
    public function addAffiliate(Request $request)
    {
        try{
            $user = new user();
            $user->name = $request->name;
            $user->password = bcrypt($request->password);
            $user->email = $request->email;
            $user->status = 1;
            $user->role = 'affiliate';
            $user->save();

            $campaign = Campaign::where('key',$request->key)->first();

            $url = $campaign->sales_url != ''?$campaign->sales_url:$campaign->campaign_url;

            $affiliate = new Affiliate();
            $affiliate->campaign_id = $campaign->id;
            $affiliate->user_id = $user->id;
            $affiliate->key = AgencyController::generateRandomString(16);
            $affiliate->approve_status = 1;
            $affiliate->save();

            $mailNotification = (new SendRegistrationSms($user->name,$user->email,$request->password,$affiliate->key,$url,$campaign->name));
            $this->dispatch($mailNotification);

            return response()->json([
                'success' => true,
                'message' => 'Affiliate Created Successfully'
            ],200);
        } catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ],500);
        }
    }
    public function approveAffiliate(Request $request)
    {
        try{
            $affiliate = Affiliate::find($request->id);
            $campaign = Campaign::find($affiliate->campaign_id);
            $user = User::find($affiliate->user_id);
            if($campaign->user_id != Auth::user()->id){
                return response()->json([
                    'success' => false,
                    'message' => 'You are Not Allowed to accept the Request'
                ],400);
            } else {
                $affiliate->approve_status = 1;
                $affiliate->save();
                Mail::send('email.approve', ['affiliateUser' => $user,'campaign' => $campaign,'user' => Auth::user(),'affiliate' => $affiliate], function ($m) use ($user) {
                    $m->from(env('MAIL_USERNAME'), 'affiliate');
                    $m->to($user->email,'Approval of Affiliate')->subject('Approval');
                });
                return response()->json([
                    'success' => true,
                    'message' => 'Affiliate Approved Successfully'
                ],200);
            }
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }
    public function deleteAffiliate(Request $request)
    {
        try{
            $affiliate = Affiliate::find($request->id);
            $affiliate->delete();
            return response()->json([
                'success' => true,
                'message' => 'Campaign Deleted Successfully'
            ],200);
        } catch(\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ],500);
        }
    }
    public function campaignProduct($id)
    {
        try{
            $campaign= Campaign::find($id);
            $products=$campaign->products()->orderBy('created_at', 'DESC')->get();
            return view('campaign.product',['campaign' => $campaign,'campaign_id' => $id,'products' => $products]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error',$exception->getMessage());
        }
    }
    public function changeStripeMode(Request $request)
    {
        try{
            $campaign = Campaign::find($request->campaign);
            if($campaign->stripe_mode == 1){
                $campaign->stripe_mode = 2;
                $message = 'Campaign is in Live mode now';
            } else {
                $campaign->stripe_mode = 1;
                $message = 'Campaign is in Test mode now';
            }
            $campaign->update();
            return response()->json([
                'success' => true,
                'message' => $message
            ],200);
        } catch(\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ],200);
        }
    }
}
