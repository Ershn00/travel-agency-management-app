<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required'],
            'description' => ['required'],
            'start_date'  => ['required', 'date'],
            'price'       => ['required', 'numeric'],
            'image'       => ['image', 'nullable'],
            'rep_id'      => ['required', 'exists:users,id'],
        ];
    }
}
