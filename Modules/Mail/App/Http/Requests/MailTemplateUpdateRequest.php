<?php

namespace Modules\Mail\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MailTemplateUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; /* TODO: permission */ }

    public function rules(): array {
        return [
            'name' => ['sometimes','string','max:190'],
            'code' => ['sometimes','string','max:190', Rule::unique('mail_templates','code')->ignore($this->route('id'))],
            'subject' => ['sometimes','string','max:255'],
            'body' => ['sometimes','string'],
            'placeholders' => ['nullable','array'],
            'placeholders.*' => ['string'],
            'is_html' => ['boolean'],
            'enabled' => ['boolean'],
        ];
    }
}
