<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EnergyReset;
use App\Models\Monitorings;
use App\Models\Taxes;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Pest\Laravel\json;

class MonitoringApiController extends Controller
{
    public function checkResetkWh() : JsonResponse {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $reset = EnergyReset::firstOrCreate(
            ['month' => $currentMonth, 'year' => $currentYear],
            ['reset_requested' => false, 'reset_completed' => false]
        );

        $requiredReset = $reset->reset_requested && !$reset->reset_completed;

        return response()->json([
            'required_reset' => $requiredReset,
            'message' => $requiredReset ? 'Please Reset Energy Counter' : 'No Reset Needed',
            'month' => $currentMonth,
            'year' => $currentYear
        ]);
    }

    public function confirmReset(Request $request) : JsonResponse {
        $validated = $request->validate([
            'success' => 'required|boolean'
        ]);

        $now = Carbon::now();
        $reset = EnergyReset::where('month', $now->month)->where('year', $now->year)->first();

        if(!$reset) {
            return response()->json([
                'success' => false,
                'message' => 'No Reset Record Found'
            ], 404);
        }

        if ($validated['success']) {
            $reset->update([
                'reset_completed' => true,
                'completed_at' => $now
            ]);
            Log::info('Energy Reset Completed', ['month' => $now->month, 'year' => $now->year]);
            return response()->json([
                'success' => true,
                'message' => 'Reset Confirmed'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Reset Failed'
        ], 500);
    }

    private function autoCheckedReset() : void {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $existingReset = EnergyReset::where('month', $currentMonth)->where('year', $currentYear)->first();

        if (!$existingReset) {
            EnergyReset::create([
                'month' => $currentMonth,
                'year' => $currentYear,
                'reset_requested' => true,
                'reset_completed' => false,
                'requested_at' => $now
            ]);

            Log::info('New month detected!', [
                'month' => $currentMonth,
                'year' => $currentYear
            ]);
        }
    }

    public function postMonitoringData(Request $request) {
        $validated = $request->validate([
            'voltage' => 'required',
            'current' => 'required',
            'power'=> 'required',
            'energy'=> 'required',
            'frequency'=> 'required',
            'tax_id'=> 'required|exists:taxes,id'
        ]);

        $tax = Taxes::findOrFail($validated['tax_id']);
        $validated['total_price'] = $validated['energy'] * $tax->tax;

        $record =  Monitorings::create($validated);

        $this->autoCheckedReset();

        return response()->json([
            'success' => true,
            'message' => 'Monitoting data stored successfully!',
            'data' => [
                'id' => $record->id,
                'energy' => $record->energy,
                'total_price' => $record->total_price,
            ]
        ], 201);
    }

    public function getRealtimeChart() {
        $records = Monitorings::orderBy('created_at', 'DESC')->take(50)->get();
        $records = $records->sortBy('created_at')->values();

        return response()->json([
            'energy' => $records->pluck('energy'),
            'total_price' => $records->pluck('total_price'),
            'seconds' => $records->pluck('created_at')->map(function ($time) {
                return Carbon::parse($time)->format('s');
            }),
            'datetime' => $records->pluck('created_at')->map(function ($time) {
                return Carbon::parse($time)->format('Y-m-d H:i:s');
            }),
        ]);
    }

    public function getRealtimeMonitoringData() {
        $latest = Monitorings::latest('created_at')->first();
        $latestCost = Monitorings::where('total_price', '>', 0)->latest('created_at')->first();

        if (!$latest) {
            return response()->json([
                'energy' => 0,
                'power' => 0,
                'voltage' => 0,
                'current' => 0,
                'frequency' => 0,
                'price' => 0
            ]);
        }

        return response()->json([
            'voltage' => $latest->voltage,
            'current' => $latest->power,
            'energy' => $latest->energy,
            'power' => $latest->current,
            'frequency' => $latest->frequency ?? 50,
            'cost' => $latestCost ? $latestCost->total_price : 0,
            'datetime' => $latest->created_at
        ]);
    }

    public function getMonthlyData($month) {
        try {
            $date = Carbon::parse($month);
        } catch (\Exception $e) {
            return response() -> json([
                'message' => 'Invalid date format. Pelase Use YYYY-MM format (e.g., 2025-01)',
                'error' => true
            ], 400);
        }

        $records = Monitorings::whereYear('created_at', $date->year)
        ->whereMonth('created_at', $date->month)
        ->orderBy('created_at')
        ->get(['energy', 'created_at']);

        if ($records->isEmpty()) {
            return response()->json([
                'message' => 'No monitoring data found for' . $date->format('F Y'),
                'energy' => [],
                'datetime' => [],
                'cost' => null
            ], 404);
        }

        $dailyData = [];

        foreach ($records as $record) {
            $day = carbon::parse($record->created_at)->format('Y-m-d');

            if (!isset($dailyData[$day])) {
                $dailyData[$day] = [
                    'total' => 0,
                    'count' => 0
                ];
            }
            
            $dailyData[$day]['total'] += $record->energy;
            $dailyData[$day]['count']++;
        }

        $energy = [];
        $dates = [];

        foreach ($dailyData as $day => $data) {
            $avgEnergy = $data['total'] / $data['count'];
            $energy[] = round($avgEnergy, 2);
            $dates[] = Carbon::parse($day)->format('d');
        }

        return response()->json([
            'energy' => $energy,
            'datetime' => $dates,
        ]);
    }

    public function getMonthlyAverage() {
        $records = Monitorings::orderBy('created_at')->get();

        if ($records->isEmpty()) {
            return response()->json([
                'message' => 'No data available',
                'energy' => [],
                'datetime' => []
            ], 404);
        }

        $grouped = $records->groupBy(function ($item) {
            $date = Carbon::parse($item->created_at);
            return $date->year . '-' . $date->month;
        });

        $energy = [];
        $datetime = [];

        foreach ($grouped as $monthKey => $monthRecords) {
            $avgEnergy = $monthRecords->avg('energy');
            $firstRecord = $monthRecords->first();
            $recordDate = Carbon::parse($firstRecord->created_at);
            $monthName = Carbon::createFromDate($recordDate->year, $recordDate->month, 1)->format('M Y');

            $energy[] = round($avgEnergy, 2);
            $datetime[] = $monthName;
        }

        return response()->json([
            'energy' => $energy,
            'datetime' => $datetime
        ]);
    }

    public function getMonthlyTotalPrice() {
        $records = Monitorings::orderBy('created_at')->get();

        if ($records->isEmpty()) {
            return response()->json([
                'message' => 'No data available',
                'total_price' => [],
                'created_at' => []
            ], 404);
        }

        $grouped = $records->groupBy(function ($item) {
            $date = Carbon::parse($item->created_at);
            return $date->year . '-' . $date->month;
        });

        $totalPrice = [];
        $created_at = [];

        foreach ($grouped as $monthKey => $monthRecords) {
            $filteedZeroRecords = $monthRecords->filter(function ($record) {
                return $record->total_price > 0;
            });

            if ($filteedZeroRecords->isEmpty()) {
                continue;
            }

            $lastRecord = $filteedZeroRecords->sortByDesc('created_at')->first();
            $recordDate = Carbon::parse($lastRecord->created_at);
            $monthName = Carbon::createFromDate($recordDate->year, $recordDate->month, 1)->format('M Y');
            
            $totalPrice[] = $lastRecord->total_price;
            $datetime[] = $monthName;
        }

        return response()->json([
            'total_price' => $totalPrice,
            'datetime' => $datetime
        ]);
    }
}