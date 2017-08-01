<?php

namespace App\Http\Controllers;

use App\Affiliate;
use App\Campaign;
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
            $campaign->user_id = $request->user_id;
            $campaign->approval = $approval;
            $campaign->key = $request->key;
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
            $campaign->approval = $request->status;
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

            $affiliate = new Affiliate();
            $affiliate->campaign_id = $campaign->id;
            $affiliate->user_id = $user->id;
            $affiliate->key = AgencyController::generateRandomString(16);
            $affiliate->approve_status = 1;
            $affiliate->save();

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
            return view('campaign.product',['campaign_id' => $id]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error',$exception->getMessage());
        }
    }
}
