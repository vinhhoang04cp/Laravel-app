<?php

namespace Modules\Congress\App\Services;

use Modules\Congress\App\Jobs\ImportToCongressChunkJob;
use Modules\Congress\App\Repositories\CongressRepository;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class CongressService
{
    public function __construct(protected CongressRepository $congressRepository)
    {
    }

    public function getListCongresses()
    {
        return $this->congressRepository->getListCongresses();
    }

    public function createCongress($data)
    {
        return $this->congressRepository->createCongress($data);
    }

    public function getCongressById($id)
    {
        return $this->congressRepository->getCongressById($id);
    }

    public function updateCongress($id, $data)
    {
        return $this->congressRepository->updateCongress($id, $data);
    }

    public function deleteCongress($id)
    {
        return $this->congressRepository->deleteCongress($id);
    }

    public function importFromExcel($file, $congressId)
    {
        $data = Excel::toArray([], $file);
        $rows = $data[0] ?? [];

        if (empty($rows)) {
            throw new \Exception("File Excel không có dữ liệu.");
        }

        // Khai báo header thủ công để tránh lỗi merge cell hoặc cột trống
        $header = [
            'stt',
            'ho_ten',
            'sid',                 // Mã định danh NĐT (SID)
            'ma_nha_dau_tu',       // Mã nhà đầu tư (Investor code)
            'so_dksh',
            'ngay_cap',
            'dia_chi',
            'email',
            'dien_thoai',
            'quoc_tich',
            'slckng_chua_luu_ky',  // Số lượng chứng khoán nắm giữ (Chưa lưu ký)
            'slckng_luu_ky',       // Số lượng chứng khoán nắm giữ (Lưu ký)
            'slckng_tong',         // Số lượng chứng khoán nắm giữ (Tổng cộng)
            'slqpb_chua_luu_ky',   // Số lượng quyền phân bổ (Chưa lưu ký)
            'slqpb_luu_ky',        // Số lượng quyền phân bổ (Lưu ký)
            'slqpb_tong',          // Số lượng quyền phân bổ (Tổng cộng)
            'ty_le'
        ];

        // Bỏ 2 dòng đầu (header Excel), lấy dữ liệu từ dòng 3 trở đi
        $rows = array_slice($rows, 2);

        // Chuẩn hóa dữ liệu: ghép header + row
        $normalized = array_map(function ($row) use ($header) {
            if (count($row) < count($header)) {
                throw new \Exception("File Excel không đúng định dạng, thiếu cột.");
            }

            return array_combine($header, array_slice($row, 0, count($header)));
        }, $rows);

        // Loại bỏ dòng trống (toàn null hoặc toàn rỗng)
        $normalized = array_filter($normalized, function ($row) {
            return !empty(array_filter($row, function ($value) {
                return $value !== null && $value !== '';
            }));
        });

        // Reset lại key tránh lỗi khi chunk
        $normalized = array_values($normalized);

        $totalSlqpbTong = array_sum(array_map(function ($row) {
            return (float)($row['slqpb_tong'] ?? 0);
        }, $normalized));

        // Chunk tối đa 1000 (nếu ít hơn thì chunk theo số lượng thực tế)
        $chunks = array_chunk($normalized, min(count($normalized), 1000));

        foreach ($chunks as $chunk) {
            ImportToCongressChunkJob::dispatch($chunk, $congressId, $totalSlqpbTong);
        }

        // Trả về số bản ghi thực tế đã import để debug/log
        return [
            'count' => count($normalized),
            'message' => "Đã đọc được " . count($normalized) . " dòng dữ liệu từ file Excel."
        ];
    }

    public function getShareholdersByCongressId($congressId)
    {
        return $this->congressRepository->getShareholdersByCongressId($congressId);
    }

    public function removeShareholderFromCongress($shareholder_id, $congress_id)
    {
        $deleted = $this->congressRepository->removeShareholderFromCongress($shareholder_id, $congress_id);

        if (!$deleted) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy cổ đông trong kỳ đại hội.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Xoá cổ đông khỏi kỳ đại hội thành công.'
        ];
    }
}
