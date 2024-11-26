<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        return response()->json($user);
    }

    public function edit(Request $request){
        $validate = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
        ]);

        $isEmailRegistered = User::where("email",$validate['email'])->exists();
        if ($isEmailRegistered){
            return response()->json(["message"=>"email already registered"],409);
        }

        $user = $request->user();
        return response()->json($user);
    }

    public function changePassword(Request $request){
        $validate = $request->validate([
            'password' => 'required|string',
            'confirmPassword' => 'required|string'
        ]);

        if ($validate['password']!==$validate['confirmPassword']){
            return response()->json(["message"=>"password and confirmPassword not match"],400);
        }

        $user = $request->user();
        $getUser = User::where("id",$user->id)->first();
        $getUser->password = Hash::make($validate['password']);
        $getUser->save();

        return response()->json(["message"=>"password successfully changes"],200);
    }
}
