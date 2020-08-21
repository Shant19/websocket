<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Redirect;
use App\User;

class AuthController extends Controller
{
    public function index()
    {
    	return view('Login');
    }

    public function register()
    {
    	return view('Register');
    }

    public function login(Request $request)
    {
	    $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/');
        } else {
        	return redirect('/login');
        }
    }

    public function signup(Request $request)
    {
    	$validatedData = $request->validate([
	        'name' => 'required',
	        'email' => 'required|email|unique:users,email',
	        'password' => 'required'
	    ]);

	    $user = new User();

	    $user->name = $request->input('name');
	    $user->email = $request->input('email');
	    $user->password = Hash::make($request->input('password'));
	    $user->save();

	    return redirect('/login');
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::to('login');
    }
}
