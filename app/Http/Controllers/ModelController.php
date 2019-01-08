<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mmodel;

class ModelController extends Controller
{
    public function all(){

    	$models = Mmodel::select([
    		'mmodel.id',
    		'mmodel.name',
    		'mmodel.color',
    		'mmodel.year',
    		'manufacturer.name as manufacturer'
    	])
    	->join(
    		'manufacturer',
    		'manufacturer.id', '=', 'mmodel.manufacturer_id'
    	)
    	->orderBy('mmodel.updated_at', 'desc')
    	->get();

    	return response()->json([
    		'models' => $models
    	], 200);

    }

    public function get($id){

    	$model = Mmodel::select([
    		'mmodel.id',
    		'mmodel.name',
    		'manufacturer.name as manufacturer_name',
    		'mmodel.manufacturer_id',
    		'mmodel.color',
    		'mmodel.year',
    		'mmodel.registration_no',
    		'mmodel.note'
    	])
    	->leftJoin(
    		'manufacturer',
    		'manufacturer.id', '=', 'mmodel.manufacturer_id'
    	)
    	->where([
    		['mmodel.id', $id]
    	])
    	->first();
    	if($model){

	    	$model->manufacturer = $model->manufacturer_id;

            $model_pics = [];
            $path = config('filesystems.UPLOAD_PATH');
            $RMPs = \App\MmodelPics::where([
                ['mmodel_id', $id]
            ])
            ->get();

            foreach ($RMPs as $RMP) {
                $file_name = $RMP->pic;
                $file_path=$path.$file_name;
                if( strlen($file_name) && \Storage::exists($file_path) ){
                    $model_pics[] = [
                        'pic_url' => \Storage::url($file_path)
                    ];
                }
            }
            $model->model_pics = $model_pics;

	    	return response()->json([
                'model' => $model
	    	], 200);

    	}

    }

    public function new(Request $request){

        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:255',
            'manufacturer' => 'required|numeric',
            'color' => 'required',
            'year' => 'required|numeric|digits:4',
        ]);
        if ($validator->fails()) {
    		return response()->json([
    			'errors' => $validator->errors()
    		], 401);
        }

    	$model = new Mmodel;
    	$model->name = $request->name;
    	$model->manufacturer_id = $request->manufacturer;
    	$model->color = $request->color;
    	$model->year = $request->year;
    	$model->registration_no = $request->registration_no;
    	$model->note = $request->note;
    	$model->save();

    	return response()->json([
    		'model' => $model
    	], 200);

    }

    public function edit(Request $request){

    	$id = $request->input('id');
    	if($id){

	        $validator = \Validator::make($request->all(), [
	            'name' => 'required|max:255',
	            'manufacturer' => 'required|numeric',
	            'color' => 'required',
	            'year' => 'required|numeric|digits:4',
	        ]);
	        if ($validator->fails()) {
	    		return response()->json([
	    			'errors' => $validator->errors()
	    		], 401);
	        }

			$model = Mmodel::where([
				['id', $id]
			])
			->first();

			if($model){
				$model->name = $request->name;
				$model->manufacturer_id = $request->manufacturer_id;
				$model->color = $request->color;
				$model->year = $request->year;
				$model->registration_no = $request->registration_no;
				$model->note = $request->note;
				$model->save();

				return response()->json([
					'model' => $model
				], 200);
			}

    	}

    }

    public function remove(Request $request){

    	$id = $request->input('id');
    	if($id){

			$affect = Mmodel::where([
				['id', $id]
			])
			->delete();

			if($affect){

		    	return response()->json([
		    		'success' => true,
		    		'message' => 'Deleted Successfully'
		    	], 200);

			}

    	}

    }

    public function inventories(){

    	$inventories = Mmodel::select([
    		'mmodel.id',
    		'mmodel.name',
    		'manufacturer.name as manufacturer',
    		\DB::raw('count(mmodel.id) as count')
    	])
    	->join(
    		'manufacturer',
    		'manufacturer.id', '=', 'mmodel.manufacturer_id'
    	)
    	->groupBy('mmodel.name')
    	->groupBy('manufacturer.name')
    	->orderBy('mmodel.updated_at', 'desc')
    	->get();

    	return response()->json([
    		'inventories' => $inventories
    	], 200);

    }

    public function uploadpics(Request $request){

        $id = $request->input('id');

        if( $id && $request->hasFile('images') ){

            $posted_fileupload = $request->file('images');

            $cnt = 0;
            foreach($posted_fileupload as $file){

                $original_name = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $size = $file->getClientSize();
                $mime_type = $file->getClientMimeType();

                $file_name = auth()->id().'_'.time().str_shuffle(time()) .'.'.$ext;

                \Storage::put('upload/'.$file_name, file_get_contents($file));

                $MmodelPics = new \App\MmodelPics();
                $MmodelPics->mmodel_id = $id;
                $MmodelPics->pic = $file_name;
                $cnt += $MmodelPics->save();

            }

            return $cnt;

        }

    }

}
