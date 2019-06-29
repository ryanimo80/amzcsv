<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        # code...
        if($request->isMethod('POST')){
            $credentials = [
                'username' => $request->username,
                'password' => $request->password
            ];

            if (auth()->attempt($credentials)) {
                $token = auth()->user()->createToken('TutsForWeb')->accessToken;
                $request->session()->put('user', $token);

                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['error' => 'UnAuthorised'], 401);
            }
        }

        if(request()->session()->has('user')){
            $token = auth()->user()->createToken('TutsForWeb')->accessToken;            
            return response()->json(['token' => $token], 200);
        }
        
        return view('auth.login');

    }

    public function logout(Request $request)
    {
        # code...
        $request->session()->flush();
        // $token = $request->session()->get('user');
        // dd($token);
        // // $token->revoke();

        // dd($request->session()->all());
        // dd(auth()->user());
    }

}
