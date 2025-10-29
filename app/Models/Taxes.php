<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxes extends Model
{
    use HasFactory;

    protected $table = 'taxes';

    protected $fillable = [
        'category_tax_id',
        'tax'
    ];

    public function monitoring() {
        return $this->hasMany(Monitorings::class);
    }

    public function categoryTax() {
        return $this->belongsTo(CategoryTaxes::class);
    }
    protected static function booted() {
        static::updating(function ($taxes) {
            $categoryTaxes = CategoryTaxes::find($taxes->category_tax_id);
            if ($categoryTaxes) {
                $taxes->tax = $categoryTaxes->tax;
            }
        });
    }
}