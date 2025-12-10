<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user]);
    }

    return response()->json(['error' => 'Unauthorized'], 401);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pay/{contribution_group_id}', [PaymentController::class, 'pay']);
    Route::post('/deposit', [PaymentController::class, 'deposit']);
    Route::post('/withdraw', [PaymentController::class, 'withdraw']);
    Route::get('/payment-history', [PaymentController::class, 'getPaymentHistory']);
    Route::get('/check-balance', [PaymentController::class, 'checkBalance']);
});
