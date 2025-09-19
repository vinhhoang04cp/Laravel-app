@extends('voyager::master')

@section('page_title', 'Mail Configs')

@section('content')
    <div class="page-content browse container-fluid">

        {{-- Alerts (Voyager chuẩn) --}}
        @include('voyager::alerts')
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>
        @endif

        <div class="panel panel-bordered">
            <div class="panel-header d-flex align-items-center justify-content-between" style="padding: 15px;">
                <h3 class="panel-title">Mail Configs</h3>
                @can('add_mail_configs')
                    <a href="{{ route('mail.ui.configs.create') }}" class="btn btn-primary">
                        <i class="voyager-plus"></i> New Config
                    </a>
                @endcan
            </div>

            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:70px">ID</th>
                            <th>Name</th>
                            <th>Driver</th>
                            <th>Host</th>
                            <th>From</th>
                            <th>Active</th>
                            <th class="text-right" style="width:320px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $it)
                            <tr>
                                <td>{{ $it->id }}</td>
                                <td>{{ $it->name }}</td>
                                <td><code>{{ $it->driver }}</code></td>
                                <td>{{ $it->host }}@if($it->port):{{ $it->port }}@endif</td>
                                <td>
                                    {{ $it->from_address }}
                                    @if($it->from_name)
                                        <small class="text-muted">({{ $it->from_name }})</small>
                                    @endif
                                </td>
                                <td>
                                    @if($it->is_active)
                                        <span class="label label-success">Active</span>
                                    @else
                                        <span class="label label-default">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @can('edit_mail_configs')
                                        <a class="btn btn-sm btn-warning" href="{{ route('mail.ui.configs.edit',$it->id) }}">
                                            <i class="voyager-edit"></i> Edit
                                        </a>
                                    @endcan

                                    @can('delete_mail_configs')
                                        <form method="post" action="{{ route('mail.ui.configs.destroy',$it->id) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Delete this config?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="voyager-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endcan

                                    @can('edit_mail_configs')
                                        @if(!$it->is_active)
                                            <form method="post" action="{{ route('mail.ui.configs.activate',$it->id) }}"
                                                  class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success">
                                                    <i class="voyager-check"></i> Set Active
                                                </button>
                                            </form>
                                        @endif
                                    @endcan

                                    @can('send_test_mail')
                                        <a class="btn btn-sm btn-dark"
                                           href="{{ route('mail.ui.send.test.form',$it->id) }}">
                                            <i class="voyager-paper-plane"></i> Send test
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No data</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="clearfix">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
