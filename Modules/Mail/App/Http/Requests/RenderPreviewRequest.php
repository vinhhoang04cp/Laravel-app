<?php

namespace Modules\Mail\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RenderPreviewRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'subject' => ['required','string'],
            'body' => ['required','string'],
            'data' => ['nullable','array']
        ];
    }
}
