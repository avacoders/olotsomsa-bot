<?php

namespace App\Http\Requests\Login;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => "required|string",
            'phone_number' => ["required","string","digits:12",
                Rule::unique('users')->ignore(auth()->user()->id),]
        ];
    }
    public function messages()
    {
        return [
            'name.required' => "Ismingizni kiriting!",
            'phone_number.required' => "Telefon raqamni kiriting!",
            'phone_number.unique' => "Telefon raqam allaqachon mavjud!",
            'phone_number.digits' => "Telefon raqam formati xato mavjud!",
            'name.unique' => "Telefon raqamni kiriting!",
            'name.string' => "Ismingizni kiriting!",
        ];
    }

}
