<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
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
            "voucher_code" => ['required', 'unique:vouchers,voucher_code', 'max:10'],
			"amount" => "required",
			"valid_till_date" => "date_format:Y-m-d" 
        ];
    }
}
