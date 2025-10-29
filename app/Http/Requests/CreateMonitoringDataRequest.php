<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMonitoringDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'voltage' => 'required',
            'current' => 'required',
            'energy' => 'required',
            'power' => 'required',
            'tax_id' => 'required|exists:taxes,id',
            'total_price' => 'required',
            'datetime' => 'required'
        ];
    }
}