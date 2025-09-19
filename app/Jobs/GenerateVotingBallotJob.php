<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Shareholder\App\Models\Shareholder;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Str;

class GenerateVotingBallotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $shareholderId;

    public function __construct(int $shareholderId)
    {
        $this->shareholderId = $shareholderId;
    }

    public function handle(): void
    {
        $shareholder = Shareholder::with('congress')->findOrFail($this->shareholderId);

        // Lấy tên kỳ đại hội, bỏ dấu tiếng Việt, thay khoảng trắng bằng "_"
        $congressName = Str::slug($shareholder->congress->name, '_');

        // Tạo folder theo tên kỳ đại hội
        $folder = storage_path("app/ballots/{$congressName}");
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        // Load file mẫu phiếu biểu quyết
        $templatePath = storage_path('app/templates/9A_PHIEU_BIEU_QUYET.docx');
        $outputPath   = $folder . "/phieu_bieu_quyet_{$shareholder->id}.docx";

        $template = new TemplateProcessor($templatePath);

        // Fill dữ liệu cổ đông
        $template->setValue('ten_co_dong', $shareholder->full_name);
        $template->setValue('dia_chi', $shareholder->address ?? '');
        $template->setValue('so_dt', $shareholder->phone ?? '');
        $template->setValue('so_thu_tu', $shareholder->id);
        $template->setValue('quoc_tich', $shareholder->nationality ?? '');
        $template->setValue('so_dksh', $shareholder->ownership_registration_number ?? '');
        $template->setValue('so_co_phan', $shareholder->shares ?? 0);
        $template->setValue('so_phieu', $shareholder->shares ?? 0);

        // Lưu file phiếu biểu quyết
        $template->saveAs($outputPath);
    }
}
