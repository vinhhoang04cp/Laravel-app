<?php

namespace Modules\Mail\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailTemplateStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; /* TODO: permission */ }

    public function rules(): array {
        return [
            'name' => ['required','string','max:190'],
            'code' => ['required','string','max:190','unique:mail_templates,code'],
            'subject' => ['required','string','max:255'],
            'body' => ['required','string'],
            'placeholders' => ['nullable','array'],
            'placeholders.*' => ['string'],
            'is_html' => ['boolean'],
            'enabled' => ['boolean'],
        ];
    }
}
