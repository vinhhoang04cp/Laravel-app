<?php

namespace Modules\Mail\App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;

class MailTemplateRenderer
{
    /**
     * Render nội dung template với dữ liệu cho trước.
     * - Hỗ trợ {{ $var }}, {!! $var !!}, @{{ $var }}, dot-notation ($user.name)
     * - Tự rơi về compileString nếu Blade::render() không có (Laravel < 10)
     */
    public function safeRender(string $tpl, array $data = []): string
    {
        // 0) Chuẩn hóa data: nếu là \JsonSerializable hoặc object, convert sang array
        if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        } elseif (is_object($data)) {
            $data = (array) $data;
        }

        // 1) Chuẩn hóa cú pháp @{{ $var }} -> {{ $var }} để Blade có thể parse
        $patched = preg_replace('/@\s*\{\{/', '{{', $tpl) ?? $tpl;

        // 2) Thay các biến thành data_get($__data,'key', '')
        //    2.1) Unescaped: {!! $foo.bar !!} -> {!! data_get($__data,'foo.bar','') !!}
        $patched = preg_replace_callback(
            '/\{\!\!\s*\$?([A-Za-z_]\w*(?:\.[A-Za-z_]\w*)*)\s*\!\!\}/m',
            function ($m) {
                $key = $m[1];
                return "{!! data_get(\$__data, '{$key}', '') !!}";
            },
            $patched
        ) ?? $patched;

        //    2.2) Escaped: {{ $foo.bar }} -> {{ data_get($__data,'foo.bar','') }}
        $patched = preg_replace_callback(
            '/\{\{\s*\$?([A-Za-z_]\w*(?:\.[A-Za-z_]\w*)*)[^}]*\}\}/m',
            function ($m) {
                // Chỉ bắt phần tên biến trước filter/pipes, bỏ qua phần " | ..."
                $raw = $m[1]; // ví dụ: company_name hoặc user.name
                return "{{ data_get(\$__data, '{$raw}', '') }}";
            },
            $patched
        ) ?? $patched;

        // 3) Render bằng Blade
        // Laravel 10+: Blade::render() có sẵn
        if (method_exists(Blade::class, 'render')) {
            return Blade::render($patched, ['__data' => $data]);
        }

        // 4) Fallback cho bản cũ: compile Blade rồi eval (giống PhpEngine)
        $compiler = app('blade.compiler');
        $php = $compiler->compileString($patched);

        $__data = $data; // biến đưa vào scope cho data_get
        ob_start();
        try {
            eval('?>'.$php);
        } catch (\Throwable $e) {
            ob_end_clean();
            // Nếu lỗi, trả lại nguyên template (hoặc bạn có thể throw $e)
            return '/* Blade render error: '.$e->getMessage().' */'.$patched;
        }
        return ob_get_clean();
    }
}
