<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use function PHPUnit\Framework\isEmpty;

class AuthController extends Controller
{
    public function login(Request $request){
        $validator = validator($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember-me' => []
        ]);

        if ($validator->fails()){
            return response(json_encode([
                'token' => [],
                'message' => 'Something went wrong',
                'errors' => $validator->errors()
            ]), Response::HTTP_BAD_REQUEST);
        }

        $validated = $validator->validate();

        $user = User::where('email', '=', $validated['email'])->first();

        if ($user == null){
            return response(json_encode([
                'user' => [],
                'message' => 'Something went wrong',
                'errors' => array(['No user under this email address'])
            ]), Response::HTTP_NOT_FOUND);
        }

        if(Hash::check($validated['password'], $user->password)){
            $token = $user->createToken(time())->plainTextToken;
            return response(json_encode([
                'token' => $token,
                'message' => 'User found',
                'errors' => []
            ]), Response::HTTP_OK);
        } else {
            return response(json_encode([
                'token' => [],
                'message' => 'User not found',
                'errors' => array(['Password mismatch'])
            ]), Response::HTTP_NOT_FOUND);
        }
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }

    public function register(Request $request){
        $validator = validator($request->all(), [
            'first-name' => ['required'],
            'last-name' => ['required'],
            'username' => ['required', 'min:3', 'max:32', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()){
            return response(json_encode([
                'user' => [],
                'message' => 'Something went wrong',
                'errors' => $validator->errors(),
            ]), Response::HTTP_BAD_REQUEST);
        }

        $validated = $validator->validate();

        User::create([
            'fname' => $validated['first-name'],
            'lname' => $validated['last-name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user = User::where('username', '=', $validated['username'])->first();

        return response(json_encode([
            'user' => $user,
            'message' => 'Successfully registered',
            'errors' => [],
        ]), Response::HTTP_OK);
    }
}
