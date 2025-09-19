<?php

namespace Modules\Mail\App\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Mail\App\Services\MailTemplateRenderer;
use Modules\Mail\App\Services\PlaceholderExtractor;

class MailPreviewWebController extends Controller
{
    public function __construct(
        private MailTemplateRenderer $renderer,
        private PlaceholderExtractor $extractor
    ) {}

    public function render(Request $request) {
        $data = $request->validate([
            'subject' => ['required','string'],
            'body' => ['required','string'],
            'data' => ['nullable','array'],
        ]);
        $html = $this->renderer->safeRender($data['body'], $data['data'] ?? []);
        $subject = $this->renderer->safeRender($data['subject'], $data['data'] ?? []);
        $placeholders = $this->extractor->extract($data['subject'], $data['body']);
        return response()->json([ 'subject'=>$subject, 'html'=>$html, 'placeholders'=>$placeholders ]);
    }
}
