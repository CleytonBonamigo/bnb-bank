<?php

namespace Turno\Deposit\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest {

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
            'amount' => 'required|numeric|min:0.01|max:9999999999',
            'description' => 'required|max:255',
            'check' => 'required|image'
        ];
    }
}
