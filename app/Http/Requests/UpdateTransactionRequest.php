<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'amount' => 'required|numeric|min:0.5|max:25000',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|max:255|min:4',
            'created_at' => 'nullable|datetime',
            'remarks' => 'nullable|numeric|min:0|lte:amount',
        ];
        return $rules;
    }
}
