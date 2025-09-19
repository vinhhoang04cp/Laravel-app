<?php

namespace Modules\Mail\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailConfigUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; /* TODO: permission */ }

    public function rules(): array {
        return [
            'name' => ['sometimes','string','max:190'],
            'driver' => ['sometimes','string'],
            'host' => ['nullable','string'],
            'port' => ['nullable','integer'],
            'username' => ['nullable','string'],
            'password' => ['nullable','string'],
            'encryption' => ['nullable','string'],
            'from_address' => ['nullable','email'],
            'from_name' => ['nullable','string','max:190'],
            'reply_to' => ['nullable','email'],
            'timeout' => ['nullable','integer','min:0'],
            'is_active' => ['boolean'],
            'options' => ['nullable','array'],
        ];
    }
}
