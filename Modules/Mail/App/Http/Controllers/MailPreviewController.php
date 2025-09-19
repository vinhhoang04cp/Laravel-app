<?php

namespace Modules\Mail\App\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Mail\App\Http\Requests\RenderPreviewRequest;
use Modules\Mail\App\Services\MailTemplateRenderer;
use Modules\Mail\App\Services\PlaceholderExtractor;

class MailPreviewController extends Controller
{
    public function __construct(
        private MailTemplateRenderer $renderer,
        private PlaceholderExtractor $extractor
    ) {}

    public function render(RenderPreviewRequest $request) {
        $v = $request->validated();
        $html = $this->renderer->safeRender($v['body'], $v['data'] ?? []);
        $subject = $this->renderer->safeRender($v['subject'], $v['data'] ?? []);
        $placeholders = $this->extractor->extract($v['subject'], $v['body']);

        return response()->json([
            'subject' => $subject,
            'html' => $html,
            'placeholders' => $placeholders
        ]);
    }
}
