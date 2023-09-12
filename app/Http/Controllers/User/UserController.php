<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    public function login(Request $request){
    }

    public function fetchUser(Request $request){

        $users=User::orderBy("name");
        $count=count($users->get());
        $users=$users->get();

        return ApiController::successResponse($users, 200,$count);
    }


    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userName' => 'required',
            'name' => 'required',
            'password' => 'required', 
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $user = User::create([
            'user_name' => $request->userName,
            'password' => Hash::make($request->password),
            'user_isActive' => $request->userIsActive,
            'user_role' => $request->userRole,
            'user_email' => $request->userEmail,
            'user_phone' => $request->userPhone,
            'name' => $request->name,
            'user_level' => $request->userLevel,
        ]);

        return ApiController::successResponse($user, 200);
    }



    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userName' => 'required',
            'name' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userExist=User::orderBy("name")
        ->where([
            ["user_name",'=',$request->userName],
            ["id",'!=',$request->id],
            
            ])->first();
        if($userExist)
        {
            return response()->json("userNameExist", 422);
        }
        else{
            $user = User::where("id",'=',$request->id)
            ->update([
                'user_name' => $request->userName,
                'user_isActive' => $request->userIsActive,
                'user_role' => $request->userRole,
                'user_email' => $request->userEmail,
                'user_phone' => $request->userPhone,
                'name' => $request->name,
                'user_level' => $request->userLevel,
            ]);
    
            return ApiController::successResponse($user, 200);
        }

    }










    public function signIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Attempt to authenticate the user with the provided credentials
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['authenticated' => false], 401);
        }
        $user = User::where([
            ['users.user_name','like',$request->user_name],
        ])
        ->first();
        $data=[
            'authenticated' =>true,
            'access_token' =>$token,
            'user' =>$user,
            'token_type' =>'bearer',
        ];

        return ApiController::successResponse($data, 200);
    }
    
    
    
}
