<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('API Token')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized', 'creds'=>$credentials], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }

    public function adduser(Request $request)
    {

        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $credentials = $request->only('email', 'password');
        try {
            $user = User::factory()->create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            $x =  substr($th, 0, 12);
            return response()->json(['message' => "$x", 'creds'=>$credentials], 500);
        }

        return response()->json(['message' => "User $name with email $email has been created!", 'creds'=>$credentials], 200);
    }
}
