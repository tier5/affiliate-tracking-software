<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\User;
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
    	//echo 'I am index method';
        return view('layouts/affiliate');
       
    }

    public function addAgency(Request $request){

        
        $data = Input::all();
        $regex = '/^(http?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        //print_r($data);
        //dd($data);

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
            //dd($errors);
            //echo 999; die;    
            return redirect()->route('getAdmin')
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

            //$agency = new Agency;
            //echo 66666;  die;

            return redirect()->route('getAdmin')->with('message', 'Agency record inserted successfully'); 
            //return view('layouts/affiliate');
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

    public function show($id)
    {
    	//echo $id;
        //die;
    	// get agency by id

    	$user = \App\User::find($id);
        //echo $user->email; die;
        
    	//var_dump($user);
        $result =[
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ];
        //echo '<pre>';            
        //print_r($result);            
        //die;
        return view('layouts/dashboard',compact('result'));
        //return view('admin/package',compact('items'));
    }

    public function disable($id)
    {
    	// turn off agency and businesses

    	// pause agency and business subscriptions
    }
}
