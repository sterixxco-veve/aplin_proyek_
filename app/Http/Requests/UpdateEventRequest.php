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
            'kategori' => 'sometimes|in:study_jam,seminar,lomba,workshop',
            'tgl_mulai' => 'sometimes|date',
            'status' => 'sometimes|in:planning,ongoing,done',
        ];
    }
}