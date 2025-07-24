<?php

namespace App\Http\Controllers\Api\v1\CustomerAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class CustomerAuthController extends Controller
{

    public function register(Request $request){

        try{
            $request->validate([
                'name'=>'required|string',
                'email' => 'required|string|email|unique:users',
                'password' =>'required|string|min:8'
            ]);

            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);

            $data = [
                'user' => $user,
                'token' => $user->createToken('API Token')->plainTextToken,
            ];

            return response()->json($data,200);

        }catch(\Throwable $th){
            \Log::alert("$th");
            return response()->json('Failed to register',400);
        }
    }

    public function login(Request $request){
        try{
            $request->validate([
                'email'=>'required|email',
                'password'=>'required'
            ]);

            if(!Auth::attempt($request->only('email','password'))){
                return response()->json(['message'=>'Invalid credentials'],400);
            }

            $user = Auth::user();

            $data = [
                'user' => $user,
                'token' => $user->createToken('API Token')->plainTextToken,
            ];

            return response()->json($data,200);
        }
        catch(\Throwable $th){
            \Log::alert("$th");
            return response()->json('Failed to login',400);
        }

    }

    public function getUser(Request $request){
        try{
            $user = $request->user();
            return response()->json($user,200);
        }
        catch(\Throwable $th){
            \Log::alert("$th");
            return response()->json('Failed to get user details',400);
        }
    }

    public function logout(Request $request){
        try{
            $user = $request->user();
            $user->tokens()->delete();
            return response()->json('Logout successful',200);
        }
        catch(\Throwable $th){
            \Log::alert("$th");
            return response()->json('Failed to logout',400);
        }
    }
}
