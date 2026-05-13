<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize()
    {
        return true; // nanti bisa dikaitkan ke role
    }

    public function rules()
    {
        return [
            'id_org' => 'required|exists:organizations,id_org',
            'nama_event' => 'required|string|max:255',
            'id_event_category' => 'required|exists:event_categories,id_event_category',
            'tgl_mulai' => 'required|date',
            'status' => 'nullable|in:planning,ongoing,done',
        ];
    }
}