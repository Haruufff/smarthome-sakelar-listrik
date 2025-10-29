<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateConnectionRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index() {
        return view('pages.profile');
    }

    public function updateProfile(UpdateProfileRequest $request) : JsonResponse {
        $request->user()->update($request->validated());
        return response()->json([
            'message' => 'Profile updated successfully!',
            'data' => $request->user()
        ], 200);
    }

    public function updateConnection(UpdateConnectionRequest $request) : JsonResponse {
        $request->user()->update($request->validated());
        return response()->json([
            'message' => 'Internet Connection updated successfully!',
            'data' => $request->user()
        ], 200);
    }

    public function updatePassword(UpdatePasswordRequest $request) : JsonResponse {
        if(!Hash::check($request->current_password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided current password is incorrect.']
            ]);
        }

        $request->user()->update(['password' => Hash::make($request->new_password)]);

        return response()->json([
            'message' => 'Password changes successfully'
        ]);
    }
}