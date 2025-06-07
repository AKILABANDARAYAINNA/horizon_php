<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\MobileAgentController;
use App\Http\Controllers\Api\PropertyApiController;


use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes for Mobile App
|--------------------------------------------------------------------------
*/

// REGISTER
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'api_token' => Str::random(60),
    ]);

    return response()->json([
        'message' => 'Registration successful.',
        'token' => $user->api_token,
        'user' => $user,
    ]);
});

//  LOGIN
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    // Issue new token manually
    $user->api_token = Str::random(60);
    $user->save();

    return response()->json([
        'message' => 'Login successful.',
        'token' => $user->api_token,
        'user' => $user,
    ]);
});

// AUTHENTICATED USER
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json([
        'message' => 'Authenticated user fetched.',
        'user' => $request->user(),
    ]);
});

Route::get('/agents', [MobileAgentController::class, 'index']);
Route::post('/agent/register', [MobileAgentController::class, 'store']);


Route::middleware('auth:api')->post('/property/store', [PropertyApiController::class, 'store']);

Route::get('/properties', [PropertyApiController::class, 'index']);

Route::post('/search', [PropertyApiController::class, 'search']);

