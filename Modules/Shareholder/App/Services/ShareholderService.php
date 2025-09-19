<?php

namespace Modules\Shareholder\App\Services;

use Modules\Shareholder\App\Repositories\ShareholderRepository;

class ShareholderService
{

    public function __construct(protected ShareholderRepository $shareholderRepository)
    {
    }

    public function getDataTableShareholders()
    {
        return $this->shareholderRepository->getDataTableShareholders();
    }

    public function createShareholder($data)
    {
        return $this->shareholderRepository->createShareholder($data);
    }

    public function getShareholderById($id)
    {
        return $this->shareholderRepository->getShareholderById($id);
    }

    public function updateShareholder($id, $data)
    {
        return $this->shareholderRepository->updateShareholder($id, $data);
    }

    public function getShareholderByUserId($id)
    {
        return $this->shareholderRepository->getShareholderByUserId($id);
    }
}
