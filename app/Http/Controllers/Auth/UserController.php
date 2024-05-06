<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\JsonResponse; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\Verification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpEmail;
use App\Models\Admin;

class UserController extends Controller
{
    //
    public function login(Request $request)
{
    // Validate incoming request data
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json(['error' => 'Invalid email or password'], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    // Attempt to authenticate the user
    if (Auth::attempt($request->only('email', 'password'))) {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        // Return response with token and user data
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
            
        ], 200);
    } else {
        // If authentication fails
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}

public function admin_login(Request $request)
{
    // Validate incoming request data
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json(['error' => 'Invalid email or password'], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    // Attempt to authenticate the user
    if (Auth::attempt($request->only('email', 'password'))) {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        // Return response with token and user data
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
            
        ], 200);
    } else {
        // If authentication fails
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}

public function user_register(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }

        // Attempt to create the user
        try {
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);

            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        } catch (\Exception $e) {
            // Handle database errors
            return response()->json(['error' => 'Failed to create user. Please try again later.'], 500);
        }
    }

    public function admin_register(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }

        // Attempt to create the user
        try {
            $user = Admin::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);

            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        } catch (\Exception $e) {
            // Handle database errors
            return response()->json(['error' => 'Failed to create user. Please try again later.'], 500);
        }
    }

    public function sendOTP(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $otp = random_int(100000, 999999);
        // Generate a random 6-digit OTP

        // Store the OTP in the verification table
        Verification::updateOrCreate(
            ['email' => $user->email],
            ['otp' => $otp]
        );

        // Send the OTP via email
        Mail::to($user->email)->send(new OtpEmail($otp));

        return response()->json(['message' => 'OTP sent to email']);
    }
    

    public function verifyOTP(Request $request)
    {
        $verification = Verification::where('email', $request->input('email'))->first();

        if (!$verification || $verification->otp !== $request->input('otp')) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        // Mark the email as verified
        $user = User::where('email', $request->input('email'))->first();
        $user->email_verified_at = now();
        $user->save();

        // Delete the verification record
        $verification->delete();

        return response()->json(['message' => 'Email verified']);
    }
}