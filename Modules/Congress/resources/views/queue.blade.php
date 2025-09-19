@extends('voyager::master')

@section('content')
    <div class="container-fluid">
        <h1>Danh sách Job Histories</h1>

        <form method="GET" class="form-inline mb-3">
            <select name="status" class="form-control mr-2">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>

            <input type="date" name="start" value="{{ request('start') }}" class="form-control mr-2" placeholder="From">
            <input type="date" name="finish" value="{{ request('finish') }}" class="form-control mr-2" placeholder="To">

            <button type="submit" class="btn btn-primary">Lọc</button>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Job ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Started At</th>
                <th>Finished At</th>
                <th>Log</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($jobs as $job)
                <tr>
                    <td>{{ $job->job_id }}</td>
                    <td>{{ $job->name }}</td>
                    <td>
                        @php
                            $class = match($job->status) {
                                'failed'    => 'label label-danger',
                                'completed' => 'label label-success',
                                'pending'   => 'label label-primary',
                                'warning'   => 'label label-warning',
                            };
                        @endphp
                        <span class="{{ $class }}">{{ ucfirst($job->status) }}</span>
                    </td>
                    <td>{{ $job->started_at }}</td>
                    <td>{{ $job->finished_at }}</td>
                    <td>
                        @if($job->log_path)
                            <a href="{{ asset('storage/' . $job->log_path) }}" target="_blank">Xem log</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $jobs->links() }}
    </div>
@endsection
