<?php

namespace App\Http\Controllers;

use Cookie;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   
    public function register(Request $request)
    {
        $user =User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'password'=>Hash::make($request->input('password')),
        ]);
        return response(['message' => 'Successfully created user!','details'=>$user], 201);
    }
    public function login(Request $request)
    {
        if(!Auth::attempt($request->only('email','password'))){
            return response(['message' => 'Invalid Credetinals',], 401);
        }
        $user =Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24);
        return response(['message' => 'Login Success','details'=>$user,'token'=>$token], 200)->withCookie($cookie);
    }
    public function getUser()
    {
        $user =Auth::user();
        return response(['message' => 'Success','details'=>$user], 200);
    }
    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        return response(['message' => 'Logout Successfully..!',], 200)->withCookie($cookie);;
    }
}
