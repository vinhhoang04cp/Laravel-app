<?php

namespace Modules\Vote\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Congress\App\Models\Congress;
use Modules\Shareholder\App\Models\Shareholder;
use Modules\Vote\App\Models\Vote;
use Modules\Vote\App\Models\VoteSession;

class VoteController extends Controller
{
    public function index(Request $request)
    {
        $congresses = Congress::all(); // load danh sách kỳ đại hội
        $sessions = collect(); // mặc định rỗng
        $votes = collect(); // mặc định rỗng

        $query = Vote::with(['shareholder', 'session.congress']);

        if ($request->filled('congress_id')) {
            // load session theo congress
            $sessions = VoteSession::where('congress_id', $request->congress_id)->get();

            if ($request->filled('session_id')) {
                $query->where('vote_session_id', $request->session_id);
                $votes = $query->paginate(10)->appends($request->all());
            }
        }

        return view('vote::vote.index', compact('congresses', 'sessions', 'votes'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'vote_session_id' => 'required|exists:vote_sessions,id',
            'voter_id' => 'required|integer',
            'choice' => 'required|in:YES,NO,ABSTAIN',
            'shares' => 'required|integer|min:1',
        ]);

        Vote::create($data);
        return back()->with(['message' => 'Vote recorded successfully', 'alert-type' => 'success']);
    }

    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'shareholder_id' => 'required|exists:shareholders,id',
                'congress_id'    => 'required|exists:congresses,id',
                'choices'        => 'required|array',
                'choices.*'      => 'required|in:YES,NO,ABSTAIN',
            ]);

            $shareholder = Shareholder::find($validated['shareholder_id']);

            if (!$shareholder) {
                return response()->json(['error' => 'Thông tin cổ đông không hợp lệ']);
            }

            foreach ($validated['choices'] as $sessionId => $choice) {
                $session = VoteSession::where('congress_id', $validated['congress_id'])
                    ->findOrFail($sessionId);

                // Nếu đã vote rồi thì bỏ qua
                if (Vote::where('vote_session_id', $session->id)
                    ->where('shareholder_id', $shareholder->id)
                    ->exists()) {
                    return response()->json(['error' => 'Bạn đã bỏ phiếu cho kỳ này rồi!']);
                }

                Vote::create([
                    'vote_session_id' => $session->id,
                    'shareholder_id'  => $shareholder->id,
                    'choice'          => strtolower($choice), // YES → yes
                    'shares'          => $shareholder->allocation_total,
                ]);
            }

            return response()->json(['success' => 'Bỏ phiếu thành công!']);
        } catch (\Throwable $e) {
            \Log::error('Vote submit error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Đã xảy ra lỗi, vui lòng thử lại sau.'], 500);
        }
    }
}
