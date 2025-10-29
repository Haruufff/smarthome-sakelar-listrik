<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function getConfig() {
        $user = User::first();

        if(!$user) {
            return response()->json([
                'error' => 'No user found'
            ], 404);
        }

        return response()->json([
            'ssid' => $user->ssid,
            'password' => $user->ssid_pass
        ]);
    }
}