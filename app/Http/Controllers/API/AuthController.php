<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email:rfc,dns',
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:4',
            'full_name' => 'required',
            'phone_number' => 'required',
            'country' => 'required',
            'gender' => 'required',
            'age' => 'required'
        ]);
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        $response = [
            'status' => 'Success',
            'messages' => 'Success Registration, Login Now!'
        ];
        return response($response, 201);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required'
        ]);
        $checkEmail = User::where('email', $data['email'])->first();
        $checkPassword = Hash::check($data['password'], $checkEmail->password);
        if (!$checkEmail || !$checkPassword) {
            $response = [
                'status' => 'Gagal',
                'messages' => 'Wrong Password OR email!'
            ];

            return response($response, 200);
        } else {
            $response = [
                'status' => 'Success',
                'messages' => 'You Succesfully Login',
                'data' => $checkEmail
            ];

            return response($response, 200);
        }
    }
}
