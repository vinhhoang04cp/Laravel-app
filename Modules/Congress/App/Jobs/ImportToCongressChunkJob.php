<?php

namespace Modules\Congress\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Shareholder\App\Enums\CreateStatus;
use Modules\Shareholder\App\Models\Shareholder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ImportToCongressChunkJob implements ShouldQueue
{
    // Dùng trait hỗ trợ queue
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chunks;      // dữ liệu chunk (một mảng các dòng từ Excel)
    protected $congressId;  // id của đại hội cổ đông (congress)
    protected $totalSlqpbTong; // Tổng cộng Số lượng quyền phân bổ của cả file Excel

    /**
     * Timeout 5 phút (300 giây).
     */
    public $timeout = 300;

    /**
     * Nhận dữ liệu khi khởi tạo job
     */
    public function __construct($chunks, $congressId, $totalSlqpbTong)
    {
        $this->chunks = $chunks;
        $this->congressId = $congressId;
        $this->totalSlqpbTong = $totalSlqpbTong;
    }

    /**
     * Xử lý chính của job
     */
    public function handle(): void
    {
        $jobId = $this->job ? $this->job->getJobId() : null;
        $queue = $this->job ? $this->job->getQueue() : 'default';
        $payload = $this->job ? json_encode($this->job->payload()) : null;

        // Tạo record job_histories lúc bắt đầu
        DB::table('job_histories')->updateOrInsert(
            ['job_id' => $jobId],
            [
                'queue' => $queue,
                'name' => self::class,
                'status' => 'pending',
                'started_at' => now(),
                'payload' => $payload
            ]
        );

        // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::beginTransaction();

        $errors = []; // file gom lỗi từng dòng
        $errors_db = []; // db gom lỗi từng dòng

        try {
            // Lặp qua từng dòng trong chunk
            foreach ($this->chunks as $row) {
                try {
                    // Tìm cổ đông đã tồn tại theo congress_id + số đăng ký sở hữu
                    // Nếu chưa có thì tạo mới
                    Shareholder::firstOrCreate(
                        [
                            'congress_id' => $this->congressId,
                            'ownership_registration_number' => $row['so_dksh'],
                        ],
                        [
                            'full_name' => $row['ho_ten'] ?? null,
                            'ownership_registration_number' => $row['so_dksh'] ?? null,
                            // Chuyển định dạng ngày từ d/m/Y sang Y-m-d
                            'ownership_registration_issue_date' => !empty($row['ngay_cap'])
                                ? Carbon::createFromFormat('d/m/Y', $row['ngay_cap'])->format('Y-m-d')
                                : null,
                            'nationality' => $row['quoc_tich'] ?? null,
                            'transaction_date' => now(),
                            'address' => $row['dia_chi'] ?? null,
                            'phone' => $row['dien_thoai'] ?? null,
                            'shares' => $row['slqpb_tong'] ?? null,
                            'email' => $row['email'] ?? null,
                            'share_unregistered' => $row['slckng_chua_luu_ky'] ?? null,
                            'share_deposited' => $row['slckng_luu_ky'] ?? null,
                            'share_total' => $row['slckng_tong'] ?? null,
                            'allocation_unregistered' => $row['slqpb_chua_luu_ky'] ?? null,
                            'allocation_deposited' => $row['slqpb_luu_ky'] ?? null,
                            'allocation_total' => $row['slqpb_tong'] ?? null,
                            'sid' => $row['sid'] ?? null,
                            'investor_code' => $row['ma_nha_dau_tu'] ?? null,
                            'init_method' => CreateStatus::TOOL, // đánh dấu là import,
                        ]
                    );
                } catch (\Throwable $e) {
                    // Nếu lỗi ở 1 dòng, ghi log lỗi và tiếp tục dòng tiếp theo
                    $errors[] = "Lỗi STT {$row['stt']}";
                    $errors_db[] = "Lỗi STT {$row['stt']}: " . $e->getMessage();
                    Log::error("Lỗi STT {$row['stt']}: " . $e->getMessage(), [
                        'row' => $row,
                        'congress_id' => $this->congressId,
                    ]);
                    continue;
                }
            }

            // Nếu không có lỗi nghiêm trọng → commit dữ liệu chunk này
            DB::commit();

            if (!empty($errors)) {
                $logFolder = 'job_logs/' . date('Y-m-d');
                Storage::disk('public')->makeDirectory($logFolder);
                $logPath = $logFolder . "/job_{$jobId}.txt";
                $content = "\xEF\xBB\xBF" . implode("\n", $errors);
                Storage::disk('public')->put($logPath, $content);

                DB::table('job_histories')->where('job_id', $jobId)->update([
                    'status' => 'warning',
                    'finished_at' => now(),
                    'exception' => implode("\n", $errors_db),
                    'log_path' => $logPath
                ]);
            } else {
                DB::table('job_histories')->where('job_id', $jobId)->update([
                    'status' => 'completed',
                    'finished_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Nếu lỗi toàn chunk → rollback và ghi log critical
            DB::rollBack();

            $logFolder = 'job_logs/' . date('Y-m-d');
            Storage::disk('public')->makeDirectory($logFolder);
            $logPath = $logFolder . "/job_{$jobId}.txt";
            $content = "\xEF\xBB\xBF" . "Lỗi toàn chunk:\n" . $e->getMessage() . "\n" . $e->getTraceAsString();
            Storage::disk('public')->put($logPath, $content);

            DB::table('job_histories')->where('job_id', $jobId)->update([
                'status' => 'failed',
                'finished_at' => now(),
                'exception' => $e->getMessage(),
                'log_path' => $logPath
            ]);

            Log::critical("Lỗi toàn chunk: " . $e->getMessage(), [
                'chunk_size' => count($this->chunks),
                'congress_id' => $this->congressId,
            ]);
            throw $e; // quăng lỗi để queue xử lý retry
        }
    }
}
