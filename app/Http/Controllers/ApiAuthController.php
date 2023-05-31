<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    public function login(LoginRequest $request){
        // Check apakah data user yang diinput ada
        $user = User::where('username', $request->username)->first();

        // check password 
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'user atau password salah'
            ],401);
        }

        // generate token
        $token = $user->createToken('userToken')->plainTextToken;

        return new LoginResource([
            'message'=>'success login',
            'user'=>$user,
            'token'=>$token,
        ],200);
    }

    public function logout(Request $request){
        #hapus
        $request->user()->tokens()->delete();

        #response
        return response()->noContent();
    }
}
