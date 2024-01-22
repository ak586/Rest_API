<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class UserController extends Controller
{
    public function register(Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|max:100'
            ]);
        }catch (ValidationException $err){
            return response()->json([
                "message"=>$err->getMessage(),
                "status"=>0
            ],400);
        }

        try{
            $user= User::create($validated);
            return response()->json([
                "message"=>"created new user",
                "status"=>1,
                "user"=>$user
            ],201);
        }catch(\Exception $ex){
            return response()->json([
                "message"=>$ex->getMessage(),
                "status"=>0
            ],500);
        }
    }
}
