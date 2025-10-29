<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Switches;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SwitchesApiController extends Controller
{
    public function getSwitches(): JsonResponse {
        $switches = Switches::get();
        
        return response()->json([
            'success' => true,
            'data' => $switches
        ]);
    }
}