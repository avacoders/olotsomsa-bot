<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use function Symfony\Component\Translation\t;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'origin' => 'required|string',
            'starts' => 'required|string',
            'ends' => 'required|string',
        ];
    }
}
