<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\AgentUrl;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use \App\Agency;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();

        $result =[
            'name' => $user->name,
            'email' => $user->email,
            'url' => $user->url
        ];
        return view('agency.addAffiliate',compact('result'));
    }
    public function addAgency(Request $request){

        
        $data = Input::all();
        $regex = '/^(http?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'phone' => 'required|numeric|digits_between:10,10',
            'email' => 'required|email|min:8',
            'url' => 'regex:' . $regex,
            
        ],
        [
            'name.required' => 'Agency name is required',
            'phone.required' => 'Phone is required',
            'phone.numeric' => 'Phone number should be numeric',
            'phone.digits_between' => 'Phone number should be min and max 10 digit',
            'email.email' => 'Correct email format required',
            'url.regex' => 'Url format is incorrect',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->route('get.add.affiliate')
                ->withErrors($validator)
                ->withInput();
        }
        else{
            $user = new User;
            $user->name = $data['name'];
            $user->role = 'Agency';
            $user->phone = $data['phone'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->url = $data['url'];
            $user->save();

            return redirect()->route('get.add.affiliate')->with('message', 'Agency record inserted successfully');
        }


    }

    public function all()
    {
    	try {
		    DB::connection()->getPdo();
		} catch (\Exception $e) {
			var_dump($e);
		    die("Could not connect to the database.  Please check your configuration.");
		}

    	$agencies = \App\Agency::all()->take(5);
		//$agencies = \App\Agency::find(1);

    	// get 5 agencies

    	foreach ($agencies as $agency) {
    		var_dump($agency->name);
    	}
    }

    public function show()
    {
    	$user = Auth::user();
        $result =[
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ];
        return view('layouts/dashboard',compact('result'));
    }

    public function disable($id)
    {
    	// turn off agency and businesses

    	// pause agency and business subscriptions
    }
    public function getSettings()
    {
        $url = AgentUrl::where('user_id',Auth::user()->id)->get();
        return view('agency.settings',['url'=>$url]);
    }
    public function registerUrl(Request $request)
    {
        $parse = parse_url($request->url);
        $rootUrl = $parse['host'];
        $key = $this->generateRandomString(32);
        $url = AgentUrl::where('url',$rootUrl)->first();
        if($url == null){
            $agentUrl = new AgentUrl();
            $agentUrl->url = $rootUrl;
            $agentUrl->key = $key;
            $agentUrl->user_id = Auth::user()->id;
            $agentUrl->save();
            return redirect()->back()->with('message','URL Added successfully');
        } else {
            return redirect()->back()->with('message','This URL is already exist ');
        }


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
}
