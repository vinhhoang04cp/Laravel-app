<?php

namespace Modules\Shareholder\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShareholderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'ownership_registration_number' => 'required|string|max:255',
            'ownership_registration_issue_date' => 'required|date',
            'address' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'registration_status' => 'required|in:Đã đăng ký,Chưa đăng ký,Ủy quyền,Vừa khởi tạo',
            'transaction_date' => 'required|date',
            'share_unregistered' => 'required|numeric|min:0',
            'share_deposited' => 'required|numeric|min:0',
            'share_total' => 'required|numeric|min:0',
            'allocation_unregistered' => 'required|numeric|min:0',
            'allocation_deposited' => 'required|numeric|min:0',
            'allocation_total' => 'required|numeric|min:0',
            'sid' => 'required|string|max:255',
            'investor_code' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [

        ];
    }
}
