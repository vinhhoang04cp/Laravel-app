<?php

namespace Modules\Mail\App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Mail\App\Models\MailLog;

class MailLogController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->get('q');
        $length = (int) $request->get('length', 10); // số bản ghi mỗi trang, mặc định 10

        // Giới hạn length để tránh abuse
        if ($length > 100) {
            $length = 100;
        } elseif ($length < 1) {
            $length = 10;
        }

        $items = MailLog::query()
            ->when($q, function ($query) use ($q) {
                $query->where('to_email', 'like', "%{$q}%")
                    ->orWhere('subject', 'like', "%{$q}%")
                    ->orWhere('template_code', 'like', "%{$q}%");
            })
            ->orderByDesc('id')
            ->paginate($length)
            ->withQueryString();

        return view('mail::logs.index', compact('items', 'length'));
    }
    public function show($id)
    {
        $log = MailLog::findOrFail($id);
        return view('mail::logs.show', compact('log'));
    }
}
