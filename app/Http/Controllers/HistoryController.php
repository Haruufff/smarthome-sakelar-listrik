<?php

namespace App\Http\Controllers;

use App\Models\Monitorings;
use Carbon\Carbon;

class HistoryController extends Controller
{
    public function index(){
        return view('pages.monitoringhistory', [
            'historyMonitoring' => Monitorings::select('created_at')->orderBy('created_at', 'ASC')->get()
            ->map(function ($item) { 
                return Carbon::parse($item->created_at)->startOfMonth(); 
            })->unique()
            ->map(function ($datetime) { 
                $lastData = Monitorings::whereYear('created_at', $datetime->year)->whereMonth('created_at', $datetime->month)->orderBy('created_at', 'DESC')->first();
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