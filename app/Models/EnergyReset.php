<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergyReset extends Model
{
    protected $fillable = [
        'month',
        'year',
        'reset_required',
        'reset_completed',
        'requested_at',
        'completed_at'
    ];
}