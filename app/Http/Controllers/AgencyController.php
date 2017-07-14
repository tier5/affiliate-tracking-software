<?php

namespace App\Http\Controllers;

use \App\Agency;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    //
    public function index()
    {
    	echo 'I am index method';
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
