<?php

namespace App\Http\Controllers\Api\Auth;

use App\ApiMessages\ApiMessages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginJwtController extends Controller
{
    /**
     * @SWG\Post(
     *   tags={"Authentication"},
     *   path="/api/v1/login",
     *   summary="Login",
     *   operationId="Login",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          description="-   Enter the email",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          description="-   Enter the password",
     *          type="string",
     *      ),
     * )
    */
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

    /**
     * @SWG\Get(
     *   tags={"Authentication"},
     *   path="/api/v1/logout",
     *   summary="logout",
     *   operationId="logout",
     *   security={{"default": {}}},
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     * )
    */

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logout successfully!']);
    }

    /**
     * @SWG\Get(
     *   tags={"Authentication"},
     *   path="/api/v1/refresh",
     *   summary="refresh",
     *   operationId="refresh",
     *   security={{"default": {}}},
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     * )
    */
    public function refresh()
    {


        try {
            $token = auth('api')->refresh();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json("Não é possivel fazer o refresh do token, Não existe nenhum usuário logado.");
        }

        return response()->json([
            'token' => $token
        ]);
    }
}
