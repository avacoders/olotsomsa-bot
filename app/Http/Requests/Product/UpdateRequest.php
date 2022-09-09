<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'status' => 'nullable',
            'category_id' => 'required|numeric|exists:categories,id',
            'price' => 'required|numeric',
            'order' => 'required|numeric',
            'unit' => 'required|string'
        ];
    }
}
