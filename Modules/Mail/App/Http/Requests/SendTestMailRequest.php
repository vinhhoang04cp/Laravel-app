<?php

namespace Modules\Mail\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendTestMailRequest extends FormRequest
{
    public function authorize(): bool { return true; /* TODO: permission */ }

    public function rules(): array {
        return [
            'to'           => ['required','email'],
            // Một trong hai: template_id hoặc template_code
            'template_id'  => ['sometimes','required_without:template_code','integer','exists:mail_templates,id'],
            'template_code'=> ['sometimes','required_without:template_id','string','exists:mail_templates,code'],
            'data'         => ['nullable','array'],
            'config_id'    => ['nullable','integer','exists:mail_configs,id'],
        ];
    }
}
