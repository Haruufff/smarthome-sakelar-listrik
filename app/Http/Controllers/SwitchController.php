<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNameSwitchRequest;
use App\Http\Requests\UpdateSwitchesRequest;
use App\Models\Switches;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SwitchController extends Controller
{
    public function index() {
        return view('pages.switch', [
            'switches' => Switches::orderBy('id', 'ASC')->get()
        ]);
    }

    public function updateSwitch(UpdateSwitchesRequest $request, Switches $switches): JsonResponse {
        $switches->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Switch updated successfully',
            'data' => $switches
        ]);
    }

    public function updateNameSwitch(UpdateNameSwitchRequest $request, $switchId) : JsonResponse {
        $switch = Switches::findOrFail($switchId);

        $switch->update([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'switch name update successfully!',
            'data' => $switch
        ]);
    }
}