<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:0.5|max:25000',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|max:255|min:4',
            'remarks' => 'nullable|max:255|min:4',
            'other_id' => 'required|exists:users,id|notIn:' . auth()->id(),
            'transaction_type' => 'required|in:expense,deposit',
        ];
    }
}
