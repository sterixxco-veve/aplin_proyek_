<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_event' => 'sometimes|string|max:255',
            'id_event_category' => 'sometimes|exists:event_categories,id_event_category',
            'tgl_mulai' => 'sometimes|date',
            'status' => 'sometimes|in:planning,ongoing,done',
        ];
    }
}