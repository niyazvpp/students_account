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
        $rules = [
            'amount' => 'required|numeric|min:0.5|max:25000',
            'category_id' => 'required',
            'description' => 'nullable|max:255|min:4',
            'remarks' => 'nullable|numeric|min:0|lte:amount',
            'ids' => 'requiredUnless:exclude,1',
            'exclude' => 'nullable|in:1',
            'divide' => 'nullable|in:1',
            'class_id' => 'nullable|exists:classes,id',
            'transaction_type' => 'required|in:expense,deposit',
        ];
        if ($this->category_id != 0 || !auth()->user()->isAdmin()) {
            $rules['category_id'] = 'required|exists:categories,id';
        }
        return $rules;
    }
}
