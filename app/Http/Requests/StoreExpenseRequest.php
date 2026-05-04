<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id_event' => 'required|exists:events,id_event',
            'id_user' => 'required|exists:users,id_user',
            'id_expense_category' => 'required|exists:expense_categories,id_expense_category',
            'nama_pengeluaran' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'nomor_rekening' => 'required|string|max:50',
        ];
    }
}