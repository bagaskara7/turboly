<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Auth;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users to the web application.
    |
    */

    public function login()
    {
    	return view('login');
    }

    public function doLogin(Request $request)
    {
    	$this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $remember = $request->get('remember');
        $credentials = [
            'username' => $request->get('username'),
            'password' => $request->get('password')
        ];

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->intended('/');
        } else {
            $credentials['email'] = $credentials['username'];
            unset($credentials['username']);

            if (Auth::attempt($credentials, $remember)) {
                return redirect()->intended('/');
            } else {
                return redirect()->back()->withErrors([
                    'error' => 'Username/email and password combination could not be found!'
                ]);
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        
        return redirect('login');
    }

    public function register()
    {
    	return view('register');
    }

    public function doRegister(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required'
        ]);

    	$user = new User;

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->username = $request->get('username');
        $user->password = bcrypt($request->get('password'));

        if ($user->save()) {
            Auth::login($user);
            return redirect()->intended('/');
        } else {
            return redirect()->back()->withErrors([
                'error' => 'There were unknown error, please try again later.'
            ]);
        }
    }
}
