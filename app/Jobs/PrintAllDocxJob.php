<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PrintAllDocxJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $folderPath;
    protected string $printerName;

    /**
     * @param string $folderPath Đường dẫn tới thư mục chứa file DOCX
     * @param string $printerName Tên máy in
     */
    public function __construct(string $folderPath, string $printerName = 'MyPrinter')
    {
        $this->folderPath = $folderPath;
        $this->printerName = $printerName;
    }

    public function handle(): void
    {
        // Quét tất cả các file .docx trong thư mục
        $files = glob($this->folderPath . '/*.docx');

        foreach ($files as $file) {
            $this->printFile($file);
        }
    }

    protected function printFile(string $filePath): void
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows dùng LibreOffice
            $soffice = '"C:\\Program Files\\LibreOffice\\program\\soffice.exe"';
            $cmd = "$soffice --headless --pt " . escapeshellarg($this->printerName) . " " . escapeshellarg($filePath);
            exec($cmd);
        } else {
            // Linux
            $cmd = "libreoffice --headless --pt " . escapeshellarg($this->printerName) . " " . escapeshellarg($filePath);
            exec($cmd);
        }
    }
}
