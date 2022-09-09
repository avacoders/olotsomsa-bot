<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            "name" => "nullable|string",
            "phone_number" => "nullable|numeric|digits:12|exists:users",
            "comment" => "required|string",
            "orders" => "required",
            'order_type' => "required|numeric",
            'lat' => "nullable|numeric",
            'long' => "nullable|numeric",
        ];
    }

    public function messages()
    {
        return [
            "comment.required" => "Manzilni kiritish majburiy",
            "name.required" => "Ismni kiritish majburiy",
            "phone_number.required" => "Telefon raqamni kiritish majburiy",
            "orders.required" => "Sizda buyurtmalar mavjud emas! Iltimos buyurtmalarni tanlab keyin tasdiqlang!",
        ];
    }
}
