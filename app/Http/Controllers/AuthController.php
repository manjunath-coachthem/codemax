<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
	public $successStatus = 200;

    public function __construct(){
    	$this->middleware('auth:api', ['except' => ['login', 'register'] ]);
    }

    public function login(){
    	$credentials = request(['email', 'password']);
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $tokenObject = $user->createToken('CodeMax');
            return $this->respondWithToken($tokenObject);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                'data' => $validator->errors()
            ];
            return response()->json($response);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $data['token'] =  $user->createToken('MyApp')->accessToken;
        $data['name'] =  $user->name;

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'User register successfully.',
        ];

        return response()->json($response, 200);

    }

    public function me(){
    	return response()->json( auth('api')->user() );
    }

    public function logout(){
    	auth('api')->logout();
    	return response()->json(['message' => 'Successfully Logged Out'], 401);
    }

    public function refresh(){
    	return $this->respondWithToken( auth('api')->refresh() );
    }

    protected function respondWithToken($tokenObject){
    	return response()->json([
    		'access_token' => $tokenObject->accessToken,
    		'token_type' => 'bearer',
    		'expires_at' => $tokenObject->token->expires_at,
    		'expires_in' => 3600,
    		'user' => auth()->user()
    	]);
    }

    public function guard(){
    	return Auth::Guard('api');
    }

}
