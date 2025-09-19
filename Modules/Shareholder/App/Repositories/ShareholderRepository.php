<?php

namespace Modules\Shareholder\App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Shareholder\App\Enums\CreateStatus;
use Modules\Shareholder\App\Enums\EmailStatus;
use Modules\Shareholder\App\Models\Shareholder;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShareholderRepository
{
    public function getDataTableShareholders()
    {
        $query = Shareholder::query();

        return DataTables::of($query)
            ->addColumn('action', function ($shareholder) {
                return view('shareholder::partials.actions', [
                    'id' => $shareholder->id,
                    'name' => $shareholder->full_name
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function createShareholder($data)
    {
//        dd($data);
        $shareholder = Shareholder::create([
            'congress_id' => $data['congress_id'],
            'full_name' => $data['full_name'],
            'ownership_registration_number' => $data['ownership_registration_number'],
            'ownership_registration_issue_date' => $data['ownership_registration_issue_date'],
            'address' => $data['address'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'nationality' => $data['nationality'],
            'registration_status' => $data['registration_status'],
            'transaction_date' => $data['transaction_date'],
            'email_status' => EmailStatus::PENDING,
            'init_method' => CreateStatus::MANUAL,
            'created_at' => now(),
            'share_unregistered' => $data['share_unregistered'],
            'share_deposited' => $data['share_deposited'],
            'share_total' => $data['share_total'],
            'allocation_unregistered' => $data['allocation_unregistered'],
            'allocation_deposited' => $data['allocation_deposited'],
            'allocation_total' => $data['allocation_total'],
            'sid' => $data['sid'],
            'investor_code' => $data['investor_code'],
        ]);
        return $shareholder;
    }

    public function getShareholderById($id)
    {
        return Shareholder::findOrFail($id);
    }

    public function updateShareholder($id, $data)
    {
        return DB::transaction(function () use ($data, $id) {
            $shareholder = $this->getShareholderById($id);

            $shareholder->update($data);

            return $shareholder;
        });
    }

    public function getShareholderByUserId($id)
    {
        return Shareholder::where('user_id', 85)->first();
    }
}
