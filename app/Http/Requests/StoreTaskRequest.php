<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_event' => 'required|exists:events,id_event',
            'id_divisi' => 'required|exists:divisions,id_divisi',
            'nama_tugas' => 'required|string|max:255',
            'brief' => 'nullable|string',
            'status' => 'required|in:todo,progress,done',
            'deadline' => 'nullable|date',
        ];
    }
}