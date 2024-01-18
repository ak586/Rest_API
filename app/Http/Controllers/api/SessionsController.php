<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use \Illuminate\Support\Facades\Cache;

class SessionsController extends Controller
{
    public function login(Request $request)
    {
        $valid = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!auth()->attempt($valid)) {
            return response()->json([
                "message"=>"authentication failed",
                "status"=>1], 401);
        }

        $issued_at = time();
        $expiration_time = $issued_at + (60 * 5);
        $payload = array(
            "iat" => $issued_at,
            "exp" => $expiration_time,
            "user_id" => auth()->user()->id,
        );

        $token = JWT::encode($payload, env("JWT_SECRET_KEY"), 'HS256');
        return response($token);
    }



    public function check_login_status(Request $request)
    {
        $token = explode(' ', $request->header('Authorization'))[1];
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET_KEY'), 'HS256'));
            $id = $decoded->user_id;
            return response()->json([
                "message" => "user is logged in",
                "status" => "success",
                "user" => User::find($id),
            ]);
        } catch (\Exception $err) {
            return response()->json([
                "message" => $err->getMessage(),
                "status" => "failed"
            ],401);
        }
    }


    public function validate_token(Request $request)
    {
        $token = $request->header('token');
        $secret_key = "hii";
        try {
            JWT::decode($token, new Key(env('JWT_SECRET_KEY'), 'HS256'));
            return response()->json(["message" => "decoded successfully"]);
        } catch (\Exception $err) {

            return response()->json(["message" => $err->getMessage(),
                "status" => 0],401);
        }
    }
}
