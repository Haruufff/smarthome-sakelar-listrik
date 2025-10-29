<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTaxesRequest;
use App\Models\CategoryTaxes;
use App\Models\Monitorings;
use App\Models\Taxes;

class RealtimeController extends Controller
{
    public function index() {    
        return view('pages.monitoringrealtime', [
            'realtimeMonitoring' => Monitorings::get(),
            'taxes' => Taxes::join('category_taxes','taxes.category_tax_id','=','category_taxes.id')->get(),
            'categoryTaxes' => CategoryTaxes::get()
        ]);
    }

    public function updateTaxes(UpdateTaxesRequest $request, $taxId) {
        $taxes = Taxes::findOrFail($taxId);
        $taxes->update([
            'category_tax_id' => $request->category_tax_id
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Electric power updated successfully!',
            'data' => $taxes
        ]);
    }
}