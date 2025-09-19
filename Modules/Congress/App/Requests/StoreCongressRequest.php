<?php

namespace Modules\Congress\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCongressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:Thường niên,Bất thường,Lấy ý kiến cổ đông bằng Văn bản',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i', // gộp ngày + giờ
            'organization_form' => 'required|in:Trực tiếp,Trực tuyến,Kết hợp',
            'location' => 'required|string|max:255',
            'agenda' => 'required|array|min:1',
            'agenda.*.title' => 'required|string|max:255|distinct',
            'agenda.*.scheduled_at' => 'required|date_format:Y-m-d\TH:i',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên kỳ đại hội.',
            'type.required' => 'Vui lòng chọn loại đại hội.',
            'type.in' => 'Loại đại hội không hợp lệ.',
            'scheduled_at.required' => 'Vui lòng nhập thời gian tổ chức.',
            'scheduled_at.date_format' => 'Thời gian tổ chức phải đúng định dạng ngày giờ.',
            'scheduled_at.after' => 'Thời gian tổ chức phải lớn hơn hiện tại.',
            'organization_form.required' => 'Vui lòng chọn hình thức tổ chức.',
            'organization_form.in' => 'Hình thức tổ chức không hợp lệ.',
            'location.required' => 'Vui lòng nhập địa điểm tổ chức.',
            'agenda.required' => 'Vui lòng nhập ít nhất một nội dung chương trình.',
            'agenda.array' => 'Dữ liệu chương trình không hợp lệ.',
            'agenda.min' => 'Vui lòng nhập ít nhất một nội dung chương trình.',
            'agenda.*.required' => 'Vui lòng nhập nội dung chương trình.',
            'agenda.*.distinct' => 'Các nội dung chương trình không được trùng nhau.',
        ];
    }

}
