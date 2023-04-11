<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request, $user_id)
    {
        $user = User::find($user_id);

        if($user){
            $header = $request->header('Authorization');
          
            if ($header) {
                $apiToken = explode(' ', $request->header('Authorization'));
                $apiToken = $apiToken[1];
                if($apiToken === $user['api_token']){
                    return response()->json([
                        'success' => true,
                        'message' => 'User Found',
                        'data' => $user
                    ], 200);
                }
            }
           
        } 

        return response()->json([
            'success' => false,
            'message' => 'User Not Found',
            'data' => ''
        ], 404);
    }
}
