<?php

namespace App\Http\Requests\Login;

use Illuminate\Foundation\Http\FormRequest;

class PhoneNumberRequest extends FormRequest
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
            'phone_number' => "required|integer|digits:12"
        ];
    }
    public function messages()
    {
        return [
            'phone_number.required' => "Telefon raqamni kiritish majburiy!",
            'phone_number.integer' => "Telefon raqam faqat raqamlardan iborat bo'lishi shart!",
            'phone_number.digits' => "Telefon raqam kamida 9 ta belgidan iborat bo'lishi shart!",
        ]; // TODO: Change the autogenerated stub
    }

}