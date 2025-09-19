<?php

namespace Modules\Mail\App\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Mail\App\Http\Requests\SendTestMailRequest;
use Modules\Mail\App\Repositories\MailConfigRepositoryInterface;
use Modules\Mail\App\Repositories\MailTemplateRepositoryInterface;
use Modules\Mail\App\Services\MailService;

class MailSendController extends Controller
{
    public function __construct(
        private MailTemplateRepositoryInterface $tplRepo,
        private MailConfigRepositoryInterface $cfgRepo,
        private MailService $mailService
    ) {
        // Bảo vệ endpoint test send
//        $this->middleware(['auth', 'can:send_test_mail']);
    }

    public function sendTest(SendTestMailRequest $request)
    {
        $v = $request->validated();

        // Lấy template theo ID nếu có, không thì theo CODE
        if (!empty($v['template_id'])) {
            $tpl = $this->tplRepo->findById((int)$v['template_id']);
        } else {
            $tpl = $this->tplRepo->findByCode($v['template_code']);
        }
        abort_if(!$tpl || !$tpl->enabled, 400, 'Template unavailable');

        // Lấy config: theo ID nếu truyền vào, không thì lấy config đang active
        $cfg = null;
        if (!empty($v['config_id'])) {
            $cfg = $this->cfgRepo->findById((int)$v['config_id']);
            abort_if(!$cfg, 400, 'Config not found');
        } else {
            $cfg = $this->cfgRepo->getActive();
        }

        $this->mailService->sendUsingTemplate(
            to: $v['to'],
            tpl: $tpl,
            data: $v['data'] ?? [],
            cfg: $cfg
        );

        return response()->json(['success' => true]);
    }
}


