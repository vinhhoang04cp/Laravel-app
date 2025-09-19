@extends('voyager::master')

@section('page_title', 'Send Test Email')

@section('content')
    <div class="page-content container-fluid">
        @include('voyager::alerts')

        <div class="panel panel-bordered">
            <div class="panel-header">
                <h3 class="panel-title">Send Test Email</h3>
            </div>

            <form method="post" action="{{ route('mail.ui.send.test', $config->id) }}">
                @csrf
                <div class="panel-body">
                    <div class="row">
                        {{-- To --}}
                        <div class="form-group col-md-6 {{ $errors->has('to') ? 'has-error' : '' }}">
                            <label class="control-label">To (email)</label>
                            <input class="form-control" name="to" type="email" required value="{{ old('to') }}">
                            @if($errors->has('to')) <span class="help-block">{{ $errors->first('to') }}</span> @endif
                        </div>

                        {{-- Template --}}
                        <div class="form-group col-md-6 {{ $errors->has('template_code') ? 'has-error' : '' }}">
                            <label class="control-label">Template</label>
                            <select class="form-control" name="template_code" required>
                                @foreach($templates as $t)
                                    <option value="{{ $t->code }}" {{ old('template_code')===$t->code ? 'selected' : '' }}>
                                        {{ $t->name }} ({{ $t->code }})
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('template_code')) <span class="help-block">{{ $errors->first('template_code') }}</span> @endif
                        </div>

                        {{-- Data JSON --}}
                        <div class="form-group col-md-12 {{ $errors->has('data') ? 'has-error' : '' }}">
                            <label class="control-label">Data (JSON)</label>
                            <textarea class="form-control" rows="8" name="data">{{ old('data','{ "name":"Cường", "phone":"0909..." }') }}</textarea>
                            @if($errors->has('data')) <span class="help-block">{{ $errors->first('data') }}</span> @endif
                            <p class="help-block">Các key phải khớp với biến trong template, ví dụ <code>@{{ $name }}</code>, <code>@{{ $phone }}</code>.</p>
                        </div>
                    </div>
                </div>

                <div class="panel-footer">
                    <button class="btn btn-primary">Send</button>
                    <a href="{{ route('mail.ui.configs.index') }}" class="btn btn-default">Back</a>
                </div>
            </form>
        </div>
    </div>
@endsection

