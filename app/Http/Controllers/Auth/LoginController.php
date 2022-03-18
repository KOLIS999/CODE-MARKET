<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

use function GuzzleHttp\Promise\all;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => ['required'],
            'password'  => ['required', 'min:6']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $credentials = request(['email', 'password']);

            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respondWithToken($token);
        } catch (QueryException $e) {
            return response()->json([
                'massage'   => 'Filed' . $e->errorInfo
            ]);
        }
    }
    public function me()
    {
        return response()->json(auth()->user());
    }
    public function logout()
    {
        Auth()->logout();
        return response()->json([
            'massage'   => 'Loged Out Succsessfully',
        ]);
    }
    public function refresh()
    {
        return $this->respondWithToken(Auth()->refresh());
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
