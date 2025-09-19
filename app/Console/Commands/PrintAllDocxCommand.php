<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\PrintAllDocxJob;

class PrintAllDocxCommand extends Command
{
    protected $signature = 'print:docx {folder} {printer=MyPrinter}';
    protected $description = 'In toàn bộ DOCX trong thư mục bằng LibreOffice';

    public function handle()
    {
        $folder = $this->argument('folder');
        $printer = $this->argument('printer');

        if (!is_dir($folder)) {
            $this->error("Thư mục không tồn tại: $folder");
            return;
        }

        PrintAllDocxJob::dispatch($folder, $printer);

        $this->info("Đã gửi job in toàn bộ DOCX trong: $folder tới máy in: $printer");
    }
}
