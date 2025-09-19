<?php

namespace Modules\Shareholder\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShareholderRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cho phép validate
    }

    public function rules(): array
    {
        $rules = [
            'full_name' => 'required|string|max:255',
            'ownership_registration_number' => 'required|string|max:50',
            'registration_type' => 'required|in:register,proxy',
        ];

        // Nếu chọn ủy quyền thì validate thêm
        if ($this->registration_type === 'proxy') {
            $rules['proxy_name'] = 'required|string|max:255';
            $rules['proxy_phone'] = 'required|string|max:20';
            $rules['proxy_id']   = 'required|string|max:50';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Vui lòng nhập họ tên cổ đông',
            'ownership_registration_number.required' => 'Vui lòng nhập số ĐKSH',
            'registration_type.required' => 'Vui lòng chọn hình thức tham dự',
            'proxy_name.required' => 'Vui lòng nhập họ tên người được ủy quyền',
            'proxy_phone.required' => 'Vui lòng nhập số điện thoại người được ủy quyền',
            'proxy_id.required' => 'Vui lòng nhập CCCD/CMND/Hộ chiếu của người được ủy quyền',
        ];
    }
}
