<?php

namespace Modules\Shareholder\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Shareholder\App\Enums\RegistrationStatus;

class StoreShareholderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'congress_id' => ['required'],
            'full_name' => 'required|string|max:255',
            'ownership_registration_number' => 'required|string|max:255',
            'ownership_registration_issue_date' => 'required|date',
            'address' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'registration_status' => ['required', Rule::in(RegistrationStatus::values())],
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
