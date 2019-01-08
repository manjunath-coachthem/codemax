<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Manufacturer;

class ManufacturerController extends Controller
{
    public function all(){

    	$manufacturers = Manufacturer::orderBy('updated_at', 'desc')->get();

    	return response()->json([
    		'manufacturers' => $manufacturers
    	], 200);

    }

    public function get($id){

    	$manufacturer = Manufacturer::whereId($id)->first();

    	return response()->json([
    		'manufacturer' => $manufacturer
    	], 200);

    }

    public function new(Request $request){

    	$manufacturer = Manufacturer::create($request->only(['name']));

    	return response()->json([
    		'manufacturer' => $manufacturer
    	], 200);

    }

    public function edit(Request $request){

    	$id = $request->input('id');
    	if($id){

			$manufacturer = Manufacturer::where([
				['id', $id]
			])
			->first();

			if($manufacturer){
				$manufacturer->name = $request->name;
				$manufacturer->save();

				return response()->json([
					'manufacturer' => $manufacturer
				], 200);
			}

    	}

    }
}
