@extends('voyager::master')

@section('page_title', 'Mail Log Detail')

@section('content')
    <div class="page-content container-fluid">
        @include('voyager::alerts')

        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3 class="panel-title">Mail Log #{{ $log->id }}</h3>
            </div>

            <div class="panel-body">
                <p><strong>To:</strong> {{ $log->to_email }}</p>
                <p><strong>Subject:</strong> {{ $log->subject }}</p>
                <p><strong>Template:</strong> {{ $log->template_code }}</p>
                <p><strong>Success:</strong> {{ $log->success ? 'Yes' : 'No' }}</p>
                <p><strong>Message ID:</strong> {{ $log->message_id }}</p>
                <p><strong>Sent At:</strong> {{ $log->sent_at }}</p>
                @if($log->error)
                    <p><strong>Error:</strong> <span class="text-danger">{{ $log->error }}</span></p>
                @endif
                <hr>
                <h4>Payload</h4>
                <pre>{{ json_encode($log->payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>

                <h4>Config Snapshot</h4>
                <pre>{{ json_encode($log->config_snapshot, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
            </div>

            <div class="panel-footer">
                <a href="{{ route('mail.ui.logs.index') }}" class="btn btn-default">Back</a>
            </div>
        </div>
    </div>
@endsection
