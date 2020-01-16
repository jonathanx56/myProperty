<?php

namespace App\Http\Controllers\Api\Auth;

use App\ApiMessages\ApiMessages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginJwtController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'     =>  'required|string',
            'password'  =>  'required|string'
        ]);

        $credentials = $request->all(['email', 'password']);

        if(!$token = auth('api')->attempt($credentials))
        {
            $message = new ApiMessages('Unathorized');
            return response()->json($message->getMessage(), 401);
        }

        return response()->json(
            [
                'token' => $token
            ]
            );
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logout successfully!']);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();

        return response()->json([
            'token' => $token
        ]);
    }
}
