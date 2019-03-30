<?php

namespace App\Http\Controllers\Auth;

use Log;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Config;
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    // define the redirectPath and loginPath
    protected $redirectPath = '/index';
    protected $loginPath = '/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|between:6,20',
            'email' => 'required|email|max:255|unique:users',
            'student_id' => 'required|studentId|unique:users',
            'password' => 'required|confirmed|between:6,15',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        Log::info('create !!!: '.$data['email']);
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'student_id' => $data['student_id'],
            'password' => bcrypt($data['password']),
            'avatar_path'    =>  Config::get('phylab.defaultAvatarPath'),
            'birthday'      => '1990-01-01'
        ]);

    }


    public function postRegister(Request $request)
    {
        Log::info("data:", $request->all());
        Log::info("validate");
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            Log::info("validate faied");
            $this->throwValidationException(
                $request, $validator
            );
        }
        Log::info("started creating...");
        $user = $this->create($request->all());
        Log::info("created successfully!");
        
        // autologin?
        // Auth::login($user);
        // TOFIX: return to main view with flash message
        return redirect('/login');
    }
}
