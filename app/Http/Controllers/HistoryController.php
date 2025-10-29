<?php

namespace App\Http\Controllers;

use App\Models\Monitorings;
use Carbon\Carbon;

class HistoryController extends Controller
{
    public function index(){
        return view('pages.monitoringhistory', [
            'historyMonitoring' => Monitorings::select('datetime')->orderBy('datetime', 'ASC')->get()
            ->map(function ($item) { 
                return Carbon::parse($item->datetime)->startOfMonth(); 
            })->unique()
            ->map(function ($datetime) { 
                $lastData = Monitorings::whereYear('datetime', $datetime->year)->whereMonth('datetime', $datetime->month)->orderBy('datetime', 'DESC')->first();
                return (object) [
                    'month' => $datetime->month,
                    'year' => $datetime->year,
                    'month_name' => $datetime->format('F Y'),
                    'last_data' => $lastData
                ];
            })->values()
        ]);
    }
}