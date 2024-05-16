<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showFormRegister(){
        return view('auth.register');
    }

    public function register(Request $request){

        $requestData = $request->all();

        $validator = Validator::make($requestData, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:5|max:255',
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
        } else {
            Admin::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('email')),
            ]);
        }
    }
}
