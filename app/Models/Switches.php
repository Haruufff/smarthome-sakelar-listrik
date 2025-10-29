<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Switches extends Model
{
    protected $table = 'switches';

    protected $fillable = [
        'name',
        'state_status',
        'is_actived'
    ];
    
    protected $casts = [
        'state_status' => 'string',
        'is_actived' => 'bool'
    ];
}