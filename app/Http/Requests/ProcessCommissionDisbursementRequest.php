<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessCommissionDisbursementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'commission_id' => 'required|exists:commissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'commission_id.required' => 'Commission ID wajib diisi',
            'commission_id.exists' => 'Commission tidak ditemukan',
        ];
    }
}
