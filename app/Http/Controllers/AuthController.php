<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    //
    // Registering New User
    // 
    public function register(Request $request){
        try{
            $FormObj = $this->GetForm($request);
            $FormObj['password'] = bcrypt($FormObj['password']);
            $storeObj =  User::create($FormObj);
    
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'StoredData' => $storeObj
            ],200);
        }
        catch(\Exception $e)
        { 
            return response()->json([
                'success' => false,
                'message' => 'Please try again later.',
            ],500);
        }
    }

    // 
    // Login User Account
    // 

    public function login(Request $request){
        try{
            // Login Request Validation
            $this->validate($request, [
                'email' =>  ['required','email'],
                'password' =>  ['required','min:6'],
            ],
            [ 
                'email.required' => 'Email address is required',
                'password.required' => 'Password is required',
            ]);

            // Request of email and password
            $credentials = request(['email', 'password']);

            // Checking credentials
            if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized access', 
            ], 401);

            // Getting & Storing Access Token
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            
            ],200);

        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Please try again later.',
            ],500);
        }
        
    }

    // 
    // Logout Current User
    // 
    public function logout(Request $request)
    {
        try{
            $request->user()->token()->revoke();
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Please try again later.',
            ],500);
        }
    }


    // 
    // Custom Request Validation 
    // 
    public function GetForm(Request $request)
    { 
        return $this->validate($request, [
            'name' => ['required','min:4'],
            'email' =>  ['required','email'],
            'password' =>  ['required','min:6','confirmed'],
        ],
        [
            'name.required' => 'User name is required',
            'email.required' => 'Email address is required',
            'password.required' => 'Password is required',
        ]);
    }
}
