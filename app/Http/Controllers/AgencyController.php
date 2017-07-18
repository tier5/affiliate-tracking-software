<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

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
        //print_r($data);
       // dd($data);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'phone' => 'required|min:10',
            'email' => 'required|email|min:8',
            
        ],
        [
            'name.required' => 'Agency name is required',
            'phone.min' => 'Phone number should be 10 digit',
            'phone.required' => 'Phone is required',
            'email.email' => 'Correct email format required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            //dd($errors);
            //echo 999; die;    
            return redirect()->route('getAgency')
                ->withErrors($validator)
                ->withInput();
        }
        else{

            $agency = new Agency;
            $agency->agency_name = $data['name'];
            $agency->agency_description = $data['description'];
            $agency->agency_phone = $data['phone'];
            $agency->agency_email = $data['email'];
            
            $agency->save();
            return redirect()->route('getAgency')->with('message', 'Agency record inserted successfully'); 
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
    	echo $id;
    	// get agency by id

    	$agency = \App\Agency::find(1);

    	var_dump($agency);
    }

    public function disable($id)
    {
    	// turn off agency and businesses

    	// pause agency and business subscriptions
    }
}
