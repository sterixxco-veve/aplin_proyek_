<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_tugas' => 'sometimes|string|max:255',
            'brief' => 'nullable|string',
            'status' => 'sometimes|in:todo,progress,done',
            'deadline' => 'nullable|date',
        ];
    }
}