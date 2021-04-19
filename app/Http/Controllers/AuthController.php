<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string',
            'password' => 'required|string'

        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);
        
        $token = $user->createToken('userToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'userToken' => $token
        ]);

    }

    public function login(Request $request){

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            
            return response()->json(['message' => 'Invalid credentials']);
        }


        $token = Auth::user()->createToken('userToken')->plainTextToken;

        return response()->json([
            'user' => Auth::user(),
            'userToken' => $token
        ]);
    }

    public function logout(){

        Auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Logout']);

    }
}
