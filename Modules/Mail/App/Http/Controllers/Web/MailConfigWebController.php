<?php

namespace Modules\Mail\App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Mail\App\Http\Requests\MailConfigStoreRequest;
use Modules\Mail\App\Http\Requests\MailConfigUpdateRequest;
use Modules\Mail\App\Models\MailConfig;
use Modules\Mail\App\Models\MailTemplate;
use Modules\Mail\App\Repositories\MailConfigRepositoryInterface;
use Modules\Mail\App\Services\MailConfigService;
use Modules\Mail\App\Services\MailService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailConfigWebController extends Controller
{
    public function __construct(
        private MailConfigRepositoryInterface $repo,
        private MailConfigService $cfgService,
        private MailService $mailService
    ) {}

    public function index(Request $request)
    {
        $items = MailConfig::query()->orderByDesc('is_active')->orderBy('name')->paginate(15);
        return view('mail::configs.index', compact('items'));
    }

    public function create()
    {
        return view('mail::configs.create');
    }

    public function store(MailConfigStoreRequest $request)
    {
        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = $this->cfgService->encryptPassword($data['password']);
        }
        $item = MailConfig::create($data);
        if ($request->boolean('is_active')) {
            MailConfig::query()->update(['is_active' => false]);
            $item->is_active = true; $item->save();
        }
        return redirect()->route('mail.ui.configs.edit', $item->id)->with('status','Created');
    }

    public function edit(int $id)
    {
        $item = MailConfig::findOrFail($id);
        return view('mail::configs.edit', compact('item'));
    }

    public function update(int $id, MailConfigUpdateRequest $request)
    {
        $item = MailConfig::findOrFail($id);
        $data = $request->validated();
        if ($request->has('password')) {
            $data['password'] = $data['password'] ? $this->cfgService->encryptPassword($data['password']) : $item->password;
        }
        $item->update($data);
        if ($request->has('is_active')) {
            if ($request->boolean('is_active')) {
                MailConfig::query()->update(['is_active' => false]);
                $item->is_active = true; $item->save();
            } else {
                $item->is_active = false; $item->save();
            }
        }
        return back()->with('status','Updated');
    }

    public function destroy(int $id)
    {
        $item = MailConfig::findOrFail($id);
        $item->delete();
        return redirect()->route('mail.ui.configs.index')->with('status','Deleted');
    }

    public function activate(int $id)
    {
        $item = MailConfig::findOrFail($id);
        MailConfig::query()->update(['is_active' => false]);
        $item->is_active = true; $item->save();
        return back()->with('status','Activated');
    }

    public function sendTestForm(int $id)
    {
        $config = MailConfig::findOrFail($id);
        $templates = MailTemplate::query()->where('enabled', true)->orderBy('name')->get();
        return view('mail::configs.send_test', compact('config','templates'));
    }


    public function sendTest(Request $request, int $id)
    {
        $validated = $request->validate([
            'to'            => ['required', 'email'],
            'template_code' => ['required', 'string', 'exists:mail_templates,code'],
            'data'          => ['nullable', 'string'], // JSON string
        ]);

        // Lấy config theo ID (ví dụ trang Send Test theo từng config)
        $config = MailConfig::findOrFail($id);

        // Lấy template theo code và kiểm tra enabled
        $tpl = MailTemplate::where('code', $validated['template_code'])->firstOrFail();
        if (!$tpl->enabled) {
            return back()->withErrors(['template_code' => 'Template đang bị tắt (disabled).']);
        }

        // Decode JSON "data" an toàn, báo lỗi nếu JSON không hợp lệ
        $data = [];
        if (!empty($validated['data'])) {
            try {
                $data = json_decode($validated['data'], true, 512, JSON_THROW_ON_ERROR);
                if (!is_array($data)) {
                    $data = [];
                }
            } catch (\Throwable $e) {
                return back()->withErrors(['data' => 'JSON data không hợp lệ: '.$e->getMessage()])
                    ->withInput();
            }
        }

        // Tuỳ bạn: có thể set mặc định một số key hay dùng để tránh undefined trong template
        // $data = array_merge(['company_name' => ''], $data);

        try {
            $this->mailService->sendUsingTemplate(
                to: trim($validated['to']),
                tpl: $tpl,
                data: $data,
                cfg: $config
            );
        } catch (TransportExceptionInterface $e) {
            Log::error('Test mail send failed', ['error' => $e->getMessage()]);
            return back()->withErrors([
                'to' => 'Gửi email thất bại: '.$e->getMessage().' (kiểm tra kết nối SMTP / cấu hình)'
            ])->withInput();
        } catch (\Throwable $e) {
            Log::error('Test mail unexpected error', ['error' => $e->getMessage()]);
            return back()->withErrors([
                'to' => 'Có lỗi khi gửi mail: '.$e->getMessage()
            ])->withInput();
        }

        return back()->with([
            'message'    => 'Mail sent!',
            'alert-type' => 'success',
        ]);
    }
}
