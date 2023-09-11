<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate(
            [
                'name' => 'required',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|confirmed'
            ]
        );
        $user = User::create(['name' => $fields['name'], 'email' => $fields['email'], 'password' => bcrypt($fields['password'])]);
        $token = $user->createToken('token')->plainTextToken;
        $data = ['user' => $user, 'token' => $token];
        return response($data, Response::HTTP_CREATED);
    }

}