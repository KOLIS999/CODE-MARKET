<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  => ['required', 'min:3', 'max:50', 'unique:users'],
            'email'    => ['required', 'unique:users'],
            'role'     => ['required', 'min:4'],
            'password' => ['required', 'min:6'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $post = User::create([
                'username'  => $request->username,
                'email'     => $request->email,
                'role'      => $request->role,
                'password'  => Hash::make($request->password),
            ]);
            return response()->json([
                'massage'   => 'Created Successfully',
                'data'      => $post
            ], Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'massage'   => 'Created Filed' . $e->errorInfo
            ]);
        }
    }
}
