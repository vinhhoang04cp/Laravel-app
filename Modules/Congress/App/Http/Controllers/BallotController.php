<?php

namespace Modules\Congress\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\ExportBallotsJob;
use Illuminate\Http\Request;
use Modules\Shareholder\App\Models\Shareholder;

class BallotController extends Controller
{
    public function exportAll(Request $request)
    {
        $congressId = $request->get('congress_id');

        // Dispatch job vào queue
        ExportBallotsJob::dispatch($congressId);

        return response()->json([
            'message' => 'Đang xử lý export phiếu, vui lòng chờ...',
        ]);
    }

    public function test($congressId)
    {

        $shareholders = Shareholder::where('congress_id', $congressId)->paginate(500);
//        dd($shareholders);
        return view('congress::ballots.stand_template', compact('shareholders'));
    }

    public function exportById(Request $request)
    {
        $congressId = $request->query('congress_id');
        $shareholderId = $request->query('shareholder_id');

        $shareholder = Shareholder::where('congress_id', $congressId)
            ->where('id', $shareholderId)
            ->firstOrFail();

        return view('congress::ballots.print_by_id', compact('shareholder'));
    }
}
