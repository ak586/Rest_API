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
        if(!$token=auth()->attempt($valid)){
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



    public function log_status(Request $request)
    {
        if(!auth()->user()){
            return response()->json([
                "message"=>"You are not logged in",
                "status"=>1
            ],401);
        }
        return response()->json(auth()->user());
    }

    public function logout()
    {
        if(auth()->user()){
            auth()->logout();
            return response()->json(['message' => 'Successfully logged out'],200);
        }
        else{
            return response()->json(['message' => 'You are already logged out'],400);
        }
    }




}
