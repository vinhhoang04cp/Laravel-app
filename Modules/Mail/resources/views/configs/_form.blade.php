@extends('voyager::master')

@section('page_title', (isset($item) ? 'Edit' : 'Create').' Mail Config')

@section('content')
    <div class="page-content container-fluid">
        @include('voyager::alerts')

        <form method="post" action="{{ isset($item) ? route('mail.ui.configs.update',$item->id) : route('mail.ui.configs.store') }}">
            @csrf
            @if(isset($item)) @method('PUT') @endif

            <div class="panel panel-bordered">
                <div class="panel-header">
                    <h3 class="panel-title">{{ isset($item) ? 'Edit' : 'Create' }} Mail Config</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Name</label>
                            <input class="form-control" name="name" value="{{ old('name', $item->name ?? '') }}" required>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Driver</label>
                            <input class="form-control" name="driver" value="{{ old('driver', $item->driver ?? 'smtp') }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Active</label><br>
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $item->is_active ?? false) ? 'checked' : '' }}>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Host</label>
                            <input class="form-control" name="host" value="{{ old('host', $item->host ?? '') }}">
                        </div>

                        <div class="form-group col-md-2">
                            <label>Port</label>
                            <input class="form-control" type="number" name="port" value="{{ old('port', $item->port ?? '') }}">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Encryption</label>
                            <input class="form-control" name="encryption" value="{{ old('encryption', $item->encryption ?? '') }}" placeholder="tls/ssl">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Username</label>
                            <input class="form-control" name="username" value="{{ old('username', $item->username ?? '') }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Password (leave blank to keep)</label>
                            <input class="form-control" type="password" name="password">
                        </div>

                        <div class="form-group col-md-6">
                            <label>From Address</label>
                            <input class="form-control" name="from_address" value="{{ old('from_address', $item->from_address ?? '') }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label>From Name</label>
                            <input class="form-control" name="from_name" value="{{ old('from_name', $item->from_name ?? '') }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Reply-To</label>
                            <input class="form-control" name="reply_to" value="{{ old('reply_to', $item->reply_to ?? '') }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Timeout</label>
                            <input class="form-control" type="number" name="timeout" value="{{ old('timeout', $item->timeout ?? '') }}">
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-primary">{{ isset($item) ? 'Update' : 'Create' }}</button>
                    <a href="{{ route('mail.ui.configs.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </form>
    </div>
@endsection
