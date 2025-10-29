<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitorings extends Model
{
    protected $table = 'monitorings';

    protected $fillable = [
        'voltage',
        'current',
        'energy',
        'power',
        'frequency',
        'tax_id',
        'total_price',
        'datetime'
    ];

    public function tax() {
        return $this->belongsTo(Taxes::class);
    }
}