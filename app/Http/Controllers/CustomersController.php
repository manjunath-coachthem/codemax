<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customers;

class CustomersController extends Controller
{
    public function all(){

    	$customers = Customers::orderBy('updated_at', 'desc')->get();

    	return response()->json([
    		'customers' => $customers
    	], 200);

    }

    public function get($id){

    	$customer = Customers::whereId($id)->first();

    	return response()->json([
    		'customer' => $customer
    	], 200);

    }

    public function new(Request $request){

    	$customer = Customers::create($request->only(['name', 'email', 'phone', 'website']));

    	return response()->json([
    		'customer' => $customer
    	], 200);

    }

    public function edit(Request $request){

    	$id = $request->input('id');
    	if($id){

			$customer = Customers::where([
				['id', $id]
			])
			->first();

			if($customer){
				$customer->name = $request->name;
				$customer->email = $request->email;
				$customer->phone = $request->phone;
				$customer->website = $request->website;
				$customer->save();

				return response()->json([
					'customer' => $customer
				], 200);
			}

    	}

    }
}
