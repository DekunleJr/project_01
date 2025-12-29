<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $createNewUser;

    public function __construct(CreateNewUser $createNewUser)
    {
        $this->createNewUser = $createNewUser;
    }

    public function register(Request $request)
    {
        // 1️⃣ Use Fortify's CreateNewUser to validate and create user
        $user = $this->createNewUser->create($request->all());

        // 2️⃣ Create Sanctum token for API auth
        $token = $user->createToken('api-token')->plainTextToken;

        // 3️⃣ Return JSON for Postman
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }
}
