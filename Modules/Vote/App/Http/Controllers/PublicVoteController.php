<?php

namespace Modules\Vote\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Shareholder\App\Models\Shareholder;
use Modules\Vote\App\Models\Vote;
use Modules\Vote\App\Models\VoteSession;


class PublicVoteController extends Controller
{
    public function showForm($id, Request $request)
    {
        $session = VoteSession::findOrFail($id);

        $ownershipNumber = $request->get('ownership_registration_number');
        $otpCode = $request->get('otp_code');

        if (!$ownershipNumber || !$otpCode) {
            abort(404);
        }

        $shareholder = Shareholder::where('ownership_registration_number', $ownershipNumber)
            ->where('otp_code', $otpCode)
            ->where('otp_expires_at', '>=', now()) // ✅ OTP còn hạn
            ->first();

        if (!$shareholder) {
            abort(404);
        }

        return view('vote::vote.vote_form', compact('session', 'shareholder'));
    }

    public function submitVote($id, Request $request)
    {
        try {
            $session = VoteSession::findOrFail($id);

            $validated = $request->validate([
                'shareholder_id' => 'required|exists:shareholders,id',
                'shares' => 'required|integer|min:1',
                'choice' => 'required|in:yes,no,abstain',
                'ownership_registration_number' => 'required|string',
                'otp_code' => 'required|string',
            ]);

            // Xác thực lại cổ đông kèm OTP còn hạn
            $shareholder = Shareholder::where('id', $validated['shareholder_id'])
                ->where('ownership_registration_number', $validated['ownership_registration_number'])
                ->where('otp_code', $validated['otp_code'])
                ->where('otp_expires_at', '>=', now()) // ✅ check hết hạn
                ->first();

            if (!$shareholder) {
                return back()->with('error', 'Thông tin cổ đông không hợp lệ hoặc OTP đã hết hạn');
            }

            // Kiểm tra đã bỏ phiếu chưa
            if (Vote::where('vote_session_id', $session->id)->where('shareholder_id', $shareholder->id)->exists()) {
                return back()->with('error', 'Bạn đã bỏ phiếu cho kỳ này rồi!');
            }

            // Lưu phiếu
            Vote::create([
                'vote_session_id' => $session->id,
                'shareholder_id' => $shareholder->id,
                'choice' => $validated['choice'],
                'shares' => $validated['shares'],
            ]);

            return back()->with('success', 'Bỏ phiếu thành công!');
        } catch (\Throwable $e) {
            \Log::error('Vote submit error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Đã xảy ra lỗi, vui lòng thử lại sau.');
        }
    }
}

