<?php

namespace Modules\Shareholder\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shareholder\App\Http\Requests\ShareholderRegisterRequest;
use Modules\Shareholder\App\Models\Shareholder;
use Modules\Shareholder\App\Enums\RegistrationStatus;
use Exception;
use Modules\Shareholder\App\Services\TokenServices;

class ShareholderRegisterController extends Controller
{
    protected TokenServices $tokenService;
    public function __construct(TokenServices $tokenService){
        $this->tokenService = $tokenService;
    }
    public function showForm($token)
    {
        $shareholder = Shareholder::where('confirmation_token', $token)
            ->where('confirmation_expires_at', '>', now())
            ->where('registration_status', RegistrationStatus::PENDING)
            ->firstOrFail();

        return view('shareholder::register', compact('shareholder'));
    }

    public function submitForm(ShareholderRegisterRequest $request)
    {
        try {
            // Tìm cổ đông theo token
            $shareholder = Shareholder::where('confirmation_token', $request->confirmation_token)
                ->where('ownership_registration_number',$request->ownership_registration_number)
                ->where('registration_status', RegistrationStatus::PENDING)
                ->firstOrFail();
            // Cập nhật thông tin đăng ký
            $shareholder->full_name = $request->full_name;
            $shareholder->confirmation_token =  $this->tokenService->generate();
            $shareholder->registration_status = $request->registration_type === 'register'
                ? RegistrationStatus::REGISTER
                : RegistrationStatus::PROXY;

            if ($request->registration_type === 'proxy') {
                $shareholder->proxy_name  = $request->proxy_name;
                $shareholder->proxy_phone = $request->proxy_phone;
                $shareholder->proxy_id    = $request->proxy_id;

            } else {
                $shareholder->proxy_name  = null;
                $shareholder->proxy_phone = null;
                $shareholder->proxy_id    = null;
            }

            $shareholder->save();

            return redirect()->back()->with('success', 'Đăng ký tham dự thành công!');
        }catch(Exception $e){
//            dd($e);
            return redirect()->back()->with('error', 'Đăng ký thất bại');
        }

    }
}
