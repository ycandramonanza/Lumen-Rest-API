<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $register = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        if($register){
            return response()->json([
                'success' => true,
                'message' => 'Register Success!',
                'data' => $register
            ], 201);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Register Fail!',
                'data' => ''
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if($user && Hash::check($password, $user->password)){
            $api_token = base64_encode(Str::random(40));

            $user->update([
                'api_token' => $api_token,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login Success!',
                'data'  => [
                    'user'  => $user,
                    'api_token' => $api_token
                ]
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login Fail!',
                'data'  => ''
            ], 400);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'success' => true,
            'message' => 'Logout Success!',
            'data'  => ''
        ], 201);
    }

    // public function user()
    // {
    //     return 'Authenticated User';
    // }
}
