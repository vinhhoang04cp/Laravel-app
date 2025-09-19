@extends('voyager::master')

@section('page_title', (isset($item) ? 'Edit' : 'Create').' Mail Template')

@section('content')
    <div class="page-content container-fluid">
        @include('voyager::alerts')

        <form method="post" action="{{ isset($item) ? route('mail.ui.templates.update',$item->id) : route('mail.ui.templates.store') }}">
            @csrf
            @if(isset($item)) @method('PUT') @endif

            <div class="panel panel-bordered">
                <div class="panel-header">
                    <h3 class="panel-title">{{ isset($item) ? 'Edit' : 'Create' }} Mail Template</h3>
                </div>

                <div class="panel-body">
                    <div class="row">
                        {{-- Name --}}
                        <div class="form-group col-md-6 {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="control-label">Name</label>
                            <input class="form-control" name="name" value="{{ old('name', $item->name ?? '') }}" required>
                            @if($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        {{-- Code --}}
                        <div class="form-group col-md-6 {{ $errors->has('code') ? 'has-error' : '' }}">
                            <label class="control-label">Code (unique)</label>
                            <input class="form-control code" name="code"
                                   value="{{ old('code', $item->code ?? '') }}"
                                {{ isset($item) ? '' : 'required' }}>
                            @if($errors->has('code'))
                                <span class="help-block">{{ $errors->first('code') }}</span>
                            @endif
                        </div>

                        {{-- Subject --}}
                        <div class="form-group col-md-12 {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <label class="control-label">Subject (Blade allowed)</label>
                            <input class="form-control" name="subject" value="{{ old('subject', $item->subject ?? '') }}" required>
                            @if($errors->has('subject'))
                                <span class="help-block">{{ $errors->first('subject') }}</span>
                            @endif
                        </div>

                        {{-- Body - dùng rich_text_box của Voyager --}}
                        <div class="form-group col-md-12 {{ $errors->has('body') ? 'has-error' : '' }}">
                            <label class="control-label">Body (Blade allowed)</label>
                            @php
                                // Fake DataRow để Voyager render TinyMCE built-in
                                $dataType = (object)['name' => 'mail_templates'];
                                $row = (object)[
                                    'field' => 'body',
                                    'type' => 'rich_text_box',
                                    'display_name' => 'Body',
                                    'required' => 1,
                                    'details' => json_decode('{}'),
                                    'order' => 1
                                ];
                                $dataTypeContent = (object)[ 'body' => old('body', $item->body ?? '') ];
                            @endphp
                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                            <p class="help-block">
                                Bạn có thể dùng biến kiểu <code>@{{ $name }}</code>, <code>@{{ $phone }}</code>, <code>@{{ $address }}</code> ...
                            </p>
                            @if($errors->has('body'))
                                <span class="help-block">{{ $errors->first('body') }}</span>
                            @endif
                        </div>

                        {{-- Placeholders (hiển thị) --}}
                        <div class="form-group col-md-12">
                            <label class="control-label">Placeholders</label>
                            <div id="livePlaceholders">
                                @php $phs = is_array($item->placeholders ?? null) ? $item->placeholders : []; @endphp
                                @if(!empty($phs))
                                    @foreach($phs as $p)
                                        <span class="label label-default" style="margin-right:4px;">{{ $p }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                            <p class="help-block">List này sẽ cập nhật tự động khi bạn bấm <strong>Preview</strong>.</p>
                        </div>

                        {{-- Enabled --}}
                        <div class="form-group col-md-3">
                            <div class="checkbox" style="margin-top: 25px;">
                                <label>
                                    <input type="checkbox" name="enabled" value="1"
                                        {{ old('enabled', $item->enabled ?? true) ? 'checked' : '' }}>
                                    Enabled
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-footer">
                    <button class="btn btn-primary">{{ isset($item) ? 'Update' : 'Create' }}</button>
                    <a href="{{ route('mail.ui.templates.index') }}" class="btn btn-default">Back</a>
                    <button type="button" class="btn btn-dark" onclick="openPreview()">Preview</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Preview Modal --}}
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Preview</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Preview data (JSON)</label>
                        <textarea id="pvData" class="form-control code" rows="6">{ "name": "Cường", "phone": "0909...", "address": "123 Lê Lợi", "logo_url": "https://via.placeholder.com/180x60?text=LOGO" }</textarea>
                    </div>
                    <div class="alert alert-info">
                        <strong>Subject:</strong> <span id="pvSubject"></span>
                    </div>
                    <iframe id="pvFrame" style="width:100%; min-height:400px; border:1px solid #eee; border-radius:4px;"></iframe>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        // Lấy nội dung editor an toàn: hỗ trợ TinyMCE của Voyager (id không cố định)
        function getBodyHtml(){
            // 1) thử lấy textarea[name=body]
            var ta = document.querySelector('textarea[name="body"]');
            if (!ta) return '';

            // 2) nếu TinyMCE đã attach, tìm editor theo targetElm.name === 'body'
            if (window.tinymce && tinymce.editors && tinymce.editors.length) {
                for (var i=0;i<tinymce.editors.length;i++){
                    var ed = tinymce.editors[i];
                    if (ed && ed.targetElm && ed.targetElm.name === 'body') {
                        try { return ed.getContent(); } catch(e) {}
                    }
                }
            }
            // 3) fallback: lấy value từ textarea
            return ta.value || '';
        }

        function renderPlaceholdersBadges(arr){
            var html = (arr || []).map(function(x){
                return '<span class="label label-default" style="margin-right:4px;">'+x+'</span>';
            }).join(' ');
            if (!html) html = '<span class="text-muted">—</span>';
            var box = document.getElementById('livePlaceholders');
            if (box) box.innerHTML = html;
        }

        function openPreview(){
            var subject = document.querySelector('input[name=subject]').value;
            var body    = getBodyHtml();

            var data = {};
            try {
                data = JSON.parse(document.getElementById('pvData').value || '{}');
            } catch(e) {
                return alert('JSON data không hợp lệ');
            }

            fetch('{{ route('mail.ui.preview') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ subject: subject, body: body, data: data })
            })
                .then(function(res){
                    if (!res.ok) return res.text().then(function(t){ throw new Error('HTTP '+res.status+': '+t); });
                    return res.json();
                })
                .then(function(r){
                    document.getElementById('pvSubject').textContent = r.subject || '';
                    var doc = document.getElementById('pvFrame').contentDocument;
                    doc.open(); doc.write(r.html || ''); doc.close();

                    // CẬP NHẬT PLACEHOLDERS LIVE
                    renderPlaceholdersBadges(r.placeholders || []);

                    $('#previewModal').modal('show');
                })
                .catch(function(err){
                    alert('Preview error: ' + err.message);
                });
        }

        function detectPlaceholdersFromBody(){
            var body = getBodyHtml();
            var subject = document.querySelector('input[name=subject]').value || '';
            var content = subject + ' ' + body;

            // Regex match
            var matches = content.match(/\{\{\s*\$([a-zA-Z0-9_]+)\s*\}\}/g) || [];
            var placeholders = matches.map(function(m){
                return m.replace(/\{\{\s*\$|\s*\}\}/g, '');
            });
            renderPlaceholdersBadges([...new Set(placeholders)]);
        }

        // Bắt sự kiện khi thay đổi subject/body
        document.querySelector('input[name=subject]').addEventListener('keyup', detectPlaceholdersFromBody);
        setInterval(detectPlaceholdersFromBody, 2000); // fallback auto-scan mỗi 2s

    </script>
@endsection
