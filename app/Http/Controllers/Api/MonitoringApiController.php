<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Monitorings;
use App\Models\Taxes;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\Monitor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class MonitoringApiController extends Controller
{
    public function postMonitoringData(Request $request) {
        $validated = $request->validate([
            'voltage' => 'required',
            'current' => 'required',
            'power'=> 'required',
            'energy'=> 'required',
            'frequency'=> 'required',
            'tax_id'=> 'required|exists:taxes,id',
            'datetime' => 'required'
        ]);

        $tax = Taxes::findOrFail($validated['tax_id']);
        $validated['total_price'] = $validated['energy'] * $tax->tax;

        $record =  Monitorings::create($validated);

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
        $records = Monitorings::orderBy('datetime', 'DESC')->take(100)->get();
        $records = $records->sortBy('datetime')->values();

        return response()->json([
            'energy' => $records->pluck('energy'),
            'total_price' => $records->pluck('total_price'),
            'seconds' => $records->pluck('datetime')->map(function ($time) {
                return Carbon::parse($time)->format('s');
            }),
            'datetime' => $records->pluck('datetime')->map(function ($time) {
                return Carbon::parse($time)->format('Y-m-d H:i:s');
            }),
        ]);
    }

    public function getRealtimeMonitoringData() {
        $latest = Monitorings::latest('datetime')->first();

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
            'voltage' => $latest->energy,
            'current' => $latest->power,
            'energy' => $latest->voltage,
            'power' => $latest->current,
            'frequency' => $latest->frequency ?? 50,
            'cost' => $latest->total_price ?? 0,
            'datetime' => $latest->datetime
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

        $records = Monitorings::whereYear('datetime', $date->year)
        ->whereMonth('datetime', $date->month)
        ->orderBy('datetime')
        ->get(['energy', 'datetime']);

        if ($records->isEmpty()) {
            return response()->json([
                'message' => 'No monitoring data found for' . $date->format('F Y'),
                'energy' => [],
                'datetime' => [],
                'cost' => null
            ], 404);
        }

        $cost = Monitorings::whereYear('datetime', $date->year)->whereMonth('datetime', $date->month)->orderBy('datetime', 'DESC')->value('total_price');

        $dailyData = [];

        foreach ($records as $record) {
            $day = carbon::parse($record->datetime)->format('Y-m-d');

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
        $records = Monitorings::orderBy('datetime')->get();

        if ($records->isEmpty()) {
            return response()->json([
                'message' => 'No data available',
                'energy' => [],
                'datetime' => []
            ], 404);
        }

        $grouped = $records->groupBy(function ($item) {
            $date = Carbon::parse($item->datetime);
            return $date->year . '-' . $date->month;
        });

        $energy = [];
        $datetime = [];

        foreach ($grouped as $monthKey => $monthRecords) {
            $avgEnergy = $monthRecords->avg('energy');
            $firstRecord = $monthRecords->first();
            $recordDate = Carbon::parse($firstRecord->datetime);
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
        $records = Monitorings::orderBy('datetime')->get();

        if ($records->isEmpty()) {
            return response()->json([
                'message' => 'No data available',
                'total_price' => [],
                'datetime' => []
            ], 404);
        }

        $grouped = $records->groupBy(function ($item) {
            $date = Carbon::parse($item->datetime);
            return $date->year . '-' . $date->month;
        });

        $totalPrice = [];
        $datetime = [];

        foreach ($grouped as $monthKey => $monthRecords) {
            $lastRecord = $monthRecords->sortByDesc('datetime')->first();
            $recordDate = Carbon::parse($lastRecord->datetime);
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