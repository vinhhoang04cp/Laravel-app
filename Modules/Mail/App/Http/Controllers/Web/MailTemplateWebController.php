<?php

namespace Modules\Mail\App\Http\Controllers\Web;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Mail\App\Http\Requests\MailTemplateStoreRequest;
use Modules\Mail\App\Http\Requests\MailTemplateUpdateRequest;
use Modules\Mail\App\Models\MailTemplate;
use Modules\Mail\App\Repositories\MailTemplateRepositoryInterface;

class MailTemplateWebController extends Controller
{
    public function __construct(private MailTemplateRepositoryInterface $repo) {}

    public function index(Request $request)
    {

        $q = $request->get('q');
        $length = $request->get('length', 10);

        $items = MailTemplate::query()
            ->when($q, function($qq) use ($q){
                $qq->where('name','like',"%$q%")
                   ->orWhere('code','like',"%$q%")
                   ->orWhere('subject','like',"%$q%");
            })
            ->orderByDesc('id')
            ->paginate($length)
            ->withQueryString();

        return view('mail::templates.index', compact('items'));
    }

    public function create()
    {
        return view('mail::templates.create');
    }

    protected function extractPlaceholders(string $subject, string $body): array
    {
        preg_match_all(
            '/\{\{\s*\$([A-Za-z_]\w*(?:\.[A-Za-z_]\w*)*)\s*\}\}/',
            $subject . ' ' . $body,
            $matches
        );
        return array_values(array_unique($matches[1]));
    }

    public function store(MailTemplateStoreRequest $request)
    {
        $data = $request->validated();

        // Tìm placeholders từ subject & body
        $placeholders = $this->extractPlaceholders($request['subject'] ?? '', $request['body'] ?? '');
        // Lưu vào DB (giả sử cột placeholders là JSON)
        $data['placeholders'] = $placeholders;

        $item = MailTemplate::create($data);

        return redirect()
            ->route('mail.ui.templates.edit', $item->id)
            ->with([
                'message'    => 'Mail Template created successfully',
                'alert-type' => 'success',
            ]);
    }

    public function edit(int $id)
    {
        $item = MailTemplate::findOrFail($id);
        return view('mail::templates.edit', compact('item'));
    }

    public function update(int $id, MailTemplateUpdateRequest $request)
    {
        $item = MailTemplate::findOrFail($id);
        $item->update($request->validated());
        $data['placeholders'] = $this->extractPlaceholders($request['subject'] ?? '', $request['body'] ?? '');
        $item->update($data);
        return back()->with([
            'message'    => 'Mail Template updated successfully',
            'alert-type' => 'success',
        ]);
    }

    public function show(int $id)
    {
        $item = MailTemplate::findOrFail($id);
        return view('mail::templates.show', compact('item'));
    }

    public function destroy(int $id)
    {
        $item = MailTemplate::findOrFail($id);
        $item->delete();
        return redirect()->route('mail.ui.templates.index')->with('status','Deleted');
    }
}

