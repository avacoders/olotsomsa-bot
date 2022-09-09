<?php

namespace App\Http\Requests\Login;

use Illuminate\Foundation\Http\FormRequest;

class NameRequest extends FormRequest
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
            'name' => "required|string",
            'code' => "required|numeric|digits:5"
        ];
    }
    public function messages()
    {
        return [
            'name.required' => "Ismingizni kiriting!",
            'code.required' => "Raqam kiriting!",
            'name.string' => "Ismingizni kiriting!",
            'code.digits' => "5 ta raqamdan iborat bo'lishi kerak!",
            'code.numeric' => "5 ta raqamdan iborat bo'lishi kerak!",
        ];
    }

}
