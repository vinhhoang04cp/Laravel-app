<?php

namespace Modules\Congress\App\Repositories;

use Modules\Congress\App\Models\Congress;
use Modules\Shareholder\App\Enums\RegistrationStatus;
use Modules\Shareholder\App\Models\Shareholder;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class CongressRepository
{
    public function getListCongresses()
    {
        $query = Congress::query();

        return DataTables::of($query)
            ->editColumn('created_at', function ($congress) {
                return optional($congress->created_at)->format('d/m/Y H:i:s');
            })
            ->editColumn('scheduled_at', function ($congress) {
                return optional($congress->scheduled_at)->format('d/m/Y H:i:s');
            })
            ->addColumn('action', function ($congress) {
                return view('congress::partials.actions', [
                    'id' => $congress->id,
                    'name' => $congress->name
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function createCongress($data)
    {
        $congress = Congress::create($data);

        if (!empty($data['agenda'])) {
            $details = array_map(function ($item, $index) {
                return [
                    'order' => $index + 1,
                    'title' => $item['title'],
                    'scheduled_at' => $item['scheduled_at'] ?? null,
                    'description' => null,
                ];
            }, $data['agenda'], array_keys($data['agenda']));

            $congress->details()->createMany($details);
        }

        return $congress;
    }

    public function getCongressById($id)
    {
        return Congress::findOrFail($id);
    }

    public function updateCongress($id, $data)
    {
        $congress = Congress::findOrFail($id);
        $congress->update($data);

        if (!empty($data['agenda'])) {
            $congress->details()->delete();

            $details = array_map(function ($item, $index) {
                return [
                    'order' => $index + 1,
                    'title' => $item['title'],
                    'scheduled_at' => $item['scheduled_at'] ?? null,
                    'description' => null,
                ];
            }, $data['agenda'], array_keys($data['agenda']));

            $congress->details()->createMany($details);
        }

        return $congress;
    }

    public function deleteCongress($id)
    {
        $congress = $this->getCongressById($id);
        $congress->delete();

        return true;
    }

    public function getShareholdersByCongressId($congressId)
    {
        $shareholders = Shareholder::where('congress_id', $congressId);

        $totalAllocation = (clone $shareholders)->sum('allocation_total');

        return DataTables::of($shareholders)
            ->addIndexColumn()
            ->editColumn('registration_status', function ($row) {
                return match ($row->registration_status) {
                    RegistrationStatus::REGISTER => '<span class="badge badge-success">Đã đăng ký</span>',
                    RegistrationStatus::PENDING => '<span class="badge badge-warning">Chưa đăng ký</span>',
                    RegistrationStatus::PROXY => '<span class="badge badge-info">Ủy quyền</span>',
                    RegistrationStatus::INIT => '<span class="badge badge-secondary">Vừa khởi tạo</span>',
                };
            })
            ->addColumn('action', function ($row) {
                return '<button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $row->id . '"><i class="voyager-edit"></i> Sửa</button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '"><i class="voyager-trash"></i> Xoá</button>
                        <button type="button" class="btn btn-sm btn-success btn-vote" data-id="' . $row->id . '"><i class="voyager-check"></i> Bỏ phiếu</button>
                        <button type="button" class="btn btn-sm btn-warning btn-print" data-id="' . $row->id . '">In</button>';
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" name="shareholder_ids[]" class="row-checkbox" value="' . $row->id . '">';
            })
            ->addColumn('ratio', function ($row) use ($totalAllocation) {
                if ($totalAllocation > 0) {
                    return round(($row->allocation_total / $totalAllocation) * 100, 3) . '%';
                }
                return '100.000%';
            })
            ->rawColumns(['registration_status', 'action', 'checkbox'])
            ->with(['totalAllocation' => $totalAllocation,])
            ->make(true);
    }

    public function removeShareholderFromCongress($shareholder_id, $congress_id)
    {
        return Shareholder::where('congress_id', $congress_id)
            ->where('id', $shareholder_id)
            ->delete();
    }
}
