<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
class SessionsController extends Controller
{
    public function login(Request $request)
    {
        $valid = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!$token=auth()->setTTL(7200)->attempt($valid)){
            return response()->json(
                [
                   "message"=>'Authentication failed',
                    'status'=>0,
                ],401
            );
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'status'=>1
        ],
        200);
    }


    public function profile(Request $request)
    {
        return response()->json(auth()->user(),200);
    }

    public function logout()
    {
            auth()->logout();
            return response()->json([
                'message' => 'Successfully logged out',
                'status'=>1
            ],200);
    }



}
