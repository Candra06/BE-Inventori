<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return $request->pin;
        try {

            $request->validate([
                'pin' => 'required',
                'username' => 'required'
            ]);
            $credentials = request('pin');
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Unauthorized'
                ]);
            }
            $user = User::where('username', $request->pin)->first();

            if ($user) {
                if (password_verify($request->pin, $user->pin)) {
                    $tokenResult = $user->createToken('authToken')->plainTextToken;



                    return response()->json([
                        'status_code' => 200,
                        'access_token' => $tokenResult,
                        'token_type' => 'Bearer',
                        'data' => $user
                    ]);
                } else {
                    return response()->json([
                        'status_code' => 401,
                        'message' => 'Password Salah',

                    ]);
                }
            } else {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Username tidak terdaftar',

                ]);
            }

            // return $user;
            if (!password_verify($request->password, $user->password)) {
                throw new \Exception('Error in Login');
            }
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }
    }
}
