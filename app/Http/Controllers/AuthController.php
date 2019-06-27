<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
	public function __construct()
	{
		# code...
		// $this->middleware('auth:api')->except(['register','login']);		
	}

    public function register(Request $request)
    {

	// \DB::enableQueryLog();
      $user = User::create([
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'avatar' => 'avatar',
      ]);
      $token = $user->createToken('Laravel Password Grant Client')->accessToken;
      // $token = auth()->login($user);
	// dd(\DB::getQueryLog());


      return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
        // $credentials = [
        //     'username' => $request->username,
        //     'password' => $request->password
        // ];

        // if (auth()->attempt($credentials)) {
        //     $token = auth()->user()->createToken('TutsForWeb')->accessToken;
        //     return response()->json(['token' => $token], 200);
        // } else {
        //     return response()->json(['error' => 'UnAuthorised'], 401);
        // }

	    $user = User::where('username', $request->username)->first();
	    if ($user) {
	        if (Hash::check($request->password, $user->password)) {
	            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
			      return $this->respondWithToken($token);
	        } else {
	            $response = "Password missmatch";
	            return response($response, 422);
	        }
	    } else {
	        $response = 'User does not exist';
	        return response($response, 422);
	    }
    }

    public function index(Request $request)
    { 
    	//passport ok
	    return response()->json( $request->user() );
    }

    public function updatePassword(Request $request)
    {
      // $credentials = $request->only(['username', 'password']);\
    	$token = $request->user()->token();
    	if($token){
    	  $request->user()->password=Hash::make($request->new_password);
	      $request->user()->save();
    	}

      return $this->respondWithToken($token);
    }    

    protected function respondWithToken($token)
    {
      return response()->json([
        'access_token' => $token,
        // 'token_type' => 'bearer',
        // 'expires_in' => auth()->factory()->getTTL() * 60
      ]);
    }    

    public function logout(Request $request)
    {
    	# code...
	    $token = $request->user()->token();
	    $token->revoke();

	    $response = 'You have been succesfully logged out!';
	    return response($response, 200);    	
    }
}
