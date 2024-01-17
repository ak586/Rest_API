<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($flag)
    {
        if ($flag == 0 || $flag == 1) {
            $users = User::where('status', $flag)->get();
            if (count($users) > 0) {
                $responses = [
                    "message" => count($users) . " users found.",
                    "status" => 1,
                    'data' => $users
                ];
                return response()->json($responses, 200);
            } else {
                return response()->json(["message" => "no users found", "status" => 0], 404);
            }
        } else {
            $responses = [
                "message" => "Invalid flag it can be only zero/one",
            ];
            return response()->json($responses, 400);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => ['required'],
            "email" => ['required', 'email', 'unique:users,email'],
            "password" => ['required', 'confirmed'],
            "password_confirmation" => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        } else {

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ];
            p($data);
            DB::beginTransaction();
            try {
                User::create($data);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                p($e->getMessage());
            }


        }

        //        try {
//            $validator = $request->validate([
//                "name" => ['required'],
//                "email" => ['required', 'email', 'unique:users,email'],
//                "password" => ['required', 'confirmed'],
//                "password_confirmation" => ['required']
//            ]);
//        }catch (ValidationException $e){
//            return response()->json(['errors' => $e->errors()], 422);
//        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json([
                'message' => 'user not found',
                'status' => '0'
            ]);
        } else {
            return response()->json([
                'message' => "user found",
                'status' => 1,
                'data' => $user
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json([
                "message" => "User not found",
                "status" => 0
            ], 404);
        } else {
            $user->name=$request['name'];
            $user->email=$request['email'];
            $user->contact=$request['contact'];
            $user->pincode=$request['pincode'];
            $user->address=$request['address'];
            $user->save();
            return response()->json([
                "message"=>"User details updated successfully",
                "status"=>1,
                "user_details"=>$user
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!is_null($user)) {
            DB::beginTransaction();
            try {
                $user->delete();
                DB::commit();
                $response = [
                    "message" => "user deleted successfully",
                    "status" => 1,
                    "user" => $user
                ];
                $status_code = 200;
            } catch (\Exception $err) {
                DB::rollBack();
                $response = [
                    "message" => "internal server error",
                    "status" => 0
                ];
                $status_code = 500;
            }
            return response()->json($response, $status_code);
        } else {
            return response()->json([
                "message" => "user doesn't exist",
                "status" => 0
            ], 404);
        }
    }

public function changePassword(Request $request, string $id){
        $user=User::find($id);
        if(is_null($user)){
            return response()->json([
                "message"=>"user not found",
                "status"=>0
            ]);
        }
        else{
            if(Hash::check($request['current_password'],$user->password)){
                    if($request['confirm_password']==$request['new_password']){
                        $user->password=Hash::make($request['new_password']);
                        $user->save();
                        return response()->json([
                            "message"=>"password updated successfully"
                        ], 200);
                    }
                    else{
                        return response()->json([
                            "message"=>"Your password doesn't match",
                            "status"=>0
                            ]);
                    }
            }
            else{
                return response([
                    "message"=>"Password is incorrect",
                    "status"=>0
                ]);
            }
        }
}
}
