<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'mobile'      => 'nullable|string|max:15',
            'device_name' => 'nullable|string|max:100',
            'token'       => 'nullable|string|max:255',
            'status'      => 'boolean',
        ]);

        $user = User::create($validated);
        return response()->json($user, 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    // update, destroy ... implement as needed
}
