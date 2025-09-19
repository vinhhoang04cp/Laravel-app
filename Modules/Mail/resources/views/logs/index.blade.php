@extends('voyager::master')

@section('page_title', 'Mail Logs')

@section('content')
    <div class="page-content container-fluid">
        @include('voyager::alerts')

        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3 class="panel-title">Mail Logs</h3>
            </div>

            <div class="panel-body">
                <form method="get" class="form-inline">
                    <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Search email/subject/template code">
                    <button class="btn btn-default">Search</button>
                    @if(request()->filled('q'))
                        <a href="{{ route('mail.ui.logs.index') }}" class="btn btn-default">Clear</a>
                    @endif
                </form>

                <div class="table-responsive" style="margin-top:15px;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>To</th>
                            <th>Subject</th>
                            <th>Template</th>
                            <th>Success</th>
                            <th>Sent at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->to_email }}</td>
                                <td>{{ $log->subject }}</td>
                                <td>{{ $log->template_code }}</td>
                                <td>
                                    @if($log->success)
                                        <span class="label label-success">Yes</span>
                                    @else
                                        <span class="label label-danger">No</span>
                                    @endif
                                </td>
                                <td>{{ $log->sent_at }}</td>
                                <td>
                                    <a href="{{ route('mail.ui.logs.show', $log->id) }}" class="btn btn-sm btn-info">
                                        <i class="voyager-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No logs found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="text-center">
                    @include('partials.pagination', ['results' => $items])
{{--                    {{ $logs->links('pagination::bootstrap-4') }}--}}
                </div>
            </div>
        </div>
    </div>
@endsection
