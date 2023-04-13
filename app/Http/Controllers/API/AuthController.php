<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    //Function for Registering users
    //designing validotor

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[

            'name' => 'required',
            'email' => 'required | email',
            'password' => 'required | min:8',
            'c_password' => 'required | same:password'
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('App')->plainTextToken;
        $success['name'] = $user->name;

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User Registered !!!'
        ];

        return response()-> json($response, 200);
    }

    //Function for Login users
    //designing validotor

    public function login(Request $request)
    {        
        /* here the other way
        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'Email or Password is wrong',
            ], 422);
        }

        $user = Auth::user();

        if (!$user->is_admin) {
            Auth::logout();
            return response([
                'message' => 'You don\'t have permission to authenticate as admin'
            ], 403);
        }elseif (!$user->email_verified_at) {
            Auth::logout();
            return response([
                'message' => 'Your email address is not verified'
            ], 403);
        }else{
            $token = $user->createToken('App')->plainTextToken;
            $response = [
                'user' => new UserResource($user),
                'token' => $token
            ];

            return response()->json($response);
        }*/
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('App')->plainTextToken;
            $success['name'] = $user->name;
            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User Logged In !!!',
            ];
            return response()->json($response, 200);
        }else {
            $response = [
                'success' => false,
                'message' => 'Login Failed'
            ];
            return response()->json($response);
        }
    }
}
