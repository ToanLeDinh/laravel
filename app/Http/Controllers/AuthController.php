<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailRegister;
use App\Models\User;
use App\Models\Admin;
use App\routes\web;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showFormRegister(){
        return view('auth.register');
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:4|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('register')
                ->withErrors($validator)
                ->withInput();
        }

        if($request->input('role') == "user"){
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);
            Mail::to($request->input('email'))->send(new SendEmailRegister($request->input('name'), $request->input('email'), 'User'));
        } else {
            Admin::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('email')),
            ]);
            Mail::to($request->input('email'))->send(new SendEmailRegister($request->input('name'), $request->input('email'), 'Admin'));
        }
    }

    public function showFormLogin(){
        return view('auth.login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if ($request->role == 'admin') {
            if (Auth::guard('admin')->attempt($credentials)) {
                $request->session()->regenerate();
    
                return Redirect::to(route('home.permission'));
            }
        } else {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return Redirect::to(route('home'));
            }
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
