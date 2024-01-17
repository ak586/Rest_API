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

        $validated=$request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request['email'])->first();
        if (is_null($user)) {
            return response()->json(["message" => "user not found"]);
        }

        $issued_at = time();
        $expiration_time = $issued_at + (60 * 5);
        $payload = array(
            "iat" => $issued_at,
            "exp" => $expiration_time,
            "user_id" => $user->id,
        );
        $token = JWT::encode($payload, "hii", 'HS256');
        Cache::put('token', $token, 5 * 60);
        return response()->json([
            "message" => "Token created",
            "token" => $token
        ]);
    }


    public function logout()
    {
        if (Cache::has('token')) {
            Cache::delete('token');
            return response()->json(["message" => "Logout successful",
                "status" => "successful"
            ]);
        } else {
            return response()->json(["message" => "You are already logged out",
                "status" => "failed"
            ]);
        }
    }


    public function check_login_status()
    {
        if (Cache::has("token")) {
            $token = Cache::get('token');
            $decoded = JWT::decode($token, new Key('hii', 'HS256'));
            $user_id = $decoded->user_id;
            $user = User::find($user_id);
            return response()->json(['message' => "you are logged in ",
                "status"=>"successful",
                "decoded" => $decoded,
                "user" => $user
            ]);
        } else {
            return response()->json(['message' => "You are not logged in",
                "status"=>"failed"
            ]);

        }

    }




    public function validate_token(Request $request)
    {
        $token = $request->header('token');
        $secret_key = "hii";
        try {
            JWT::decode($token, new Key($secret_key, 'HS256'));
            return response()->json(["message" => "decoded successfully"]);
        } catch (\Exception $err) {
            return response()->json(["message" => $err->getMessage(),
                "status" => "failed"]);
        }
    }


}
