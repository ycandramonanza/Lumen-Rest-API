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
            $response = $this->checkAuth($request, $user);

            if($response['success']){
                return response()->json($response, 200);
            }
        } 

        return response()->json([
            'success' => false,
            'message' => 'User Not Found',
            'data' => ''
        ], 404);
    }

    public function update(Request $request, $user_id)
    {
        $validate = $this->validate($request, [
            'name'  => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $user = User::where('user_id', $user_id)->first();

        if($user){
                $response = $this->checkAuth($request, $user);

                if($response['success']){
                    $user->update([
                        'name'  => $validate['name'],
                        'email' => $validate['email'],
                        'password' => $validate['password']
                    ]);
                    return response()->json($response, 200);
                }
        }
       
        return response()->json([
            'success' => false,
            'message' => 'User Not Found',
            'data' => ''
        ], 404);
    }

    public function delete(Request $request, $user_id)
    {
        $user = User::where('user_id', $user_id)->first();
        if($user){
            $response = $this->checkAuth($request, $user);

            if($response['success']){
                $user->delete();
                return response()->json($response, 200);
            }
        }
    
        return response()->json([
            'success' => false,
            'message' => 'User Not Found',
            'data' => ''
        ], 404);
    }

    public function checkAuth($request, $user)
    {
        try {
            $header = $request->header('Authorization');
            if ($header) {
                $apiToken = explode(' ', $request->header('Authorization'));
                $apiToken = $apiToken[1];
                if($apiToken === $user['api_token']){
                    return ['success' => true, 'message' => 'User Found', 'data' => $user];
                }
            }

            return ['success' => false, 'message' => 'Unauthorized', 'data' => ''];

        } catch (\Exception $e) {
            //throw $th;
            return $e->getMessage();
        }
    }
}
