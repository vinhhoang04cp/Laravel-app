<?php

namespace Modules\Mail\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Mail\App\Http\Requests\MailTemplateStoreRequest;
use Modules\Mail\App\Http\Requests\MailTemplateUpdateRequest;
use Modules\Mail\App\Repositories\MailTemplateRepositoryInterface;
use Modules\Mail\App\Services\PlaceholderExtractor;

class MailTemplateController extends Controller
{
    public function __construct(
        private MailTemplateRepositoryInterface $repo,
        private PlaceholderExtractor $extractor
    ) {
        // TODO: middleware('auth'); // permission
    }

    public function index(Request $request) {
        $perPage = (int) ($request->get('per_page', 20));
        return response()->json($this->repo->paginate($perPage));
    }

    public function show(int $id) {
        $tpl = $this->repo->findById($id);
        abort_if(!$tpl, 404, 'Template not found');
        return response()->json($tpl);
    }

    public function store(MailTemplateStoreRequest $request) {
        $data = $request->validated();
        $data['placeholders'] = $this->extractor->extract($data['subject'], $data['body']);
        $tpl = $this->repo->create($data);
        return response()->json($tpl, 201);
    }

    public function update(int $id, MailTemplateUpdateRequest $request) {
        $tpl = $this->repo->findById($id);
        abort_if(!$tpl, 404, 'Template not found');
        $data = $request->validated();
        $subject = $data['subject'] ?? $tpl->subject;
        $body = $data['body'] ?? $tpl->body;
        $data['placeholders'] = $this->extractor->extract($subject, $body);
        $tpl = $this->repo->update($tpl, $data);
        return response()->json($tpl);
    }

    public function destroy(int $id) {
        $tpl = $this->repo->findById($id);
        abort_if(!$tpl, 404, 'Template not found');
        $this->repo->delete($tpl);
        return response()->json(['success' => true]);
    }
}
