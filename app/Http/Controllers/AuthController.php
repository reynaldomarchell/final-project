<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        
        $validate = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        
        $isEmailRegistered = User::where("email",$validate['email'])->exists();
        if ($isEmailRegistered){
            return response()->json(["message"=>"email already registered"],409);
        }
        
        $newUser = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'type' => 'donator',
            'password' => Hash::make($validate['password'])
        ]);

        return response()->json($newUser);
    }

    public function login(Request $request){
        $validate = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $getUser = User::where("email",$validate['email'])->first();
        if ($getUser == null){
            return response()->json(["message"=>"user with email ".$validate['email']." was not found"],404);
        }

        if (!Hash::check($validate['password'],$getUser->password)){
            return response()->json(["message"=>"password incorrect"],401);
        }

        $token = JWTAuth::fromUser($getUser);

        return response()->json([
            "message" => "login successfull",
            "token" => $token
        ],200);
    }
}
