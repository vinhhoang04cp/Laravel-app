@extends('voyager::master')

@section('page_title', 'Chi tiết Phiên Biểu Quyết')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-check"></i>
        Chi tiết Phiên Biểu Quyết
    </h1>
    <a href="{{ route('vote.ui.sessions.index') }}" class="btn btn-warning">
        <i class="voyager-angle-left"></i> <span>Quay lại</span>
    </a>
@stop

@section('content')
    <div class="page-content container-fluid">
        @include('voyager::alerts')

        <div class="row">
            <div class="col-md-10">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="voyager-info-circled"></i> Thông tin Phiên Biểu Quyết</h3>
                    </div>
                    <div class="panel-body">
                        <h3>{{ $session->title }}</h3>
                        <p>{{ $session->description }}</p>

                        <ul>
                            <li><strong>Kỳ Đại Hội:</strong> {{ $session->congress->name ?? 'N/A' }}</li>
                            <li><strong>Tỷ lệ cần đạt:</strong> {{ $session->required_percentage }}%</li>
                            <li><strong>Ngày tạo:</strong> {{ $session->created_at->format('d/m/Y H:i') }}</li>
                        </ul>
                    </div>
                </div>

                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="voyager-bar-chart"></i> Kết quả tạm thời</h3>
                    </div>
                    <div class="panel-body">
                        @php
                            $results = $session->votes()
                                ->selectRaw('choice, SUM(shares) as total_shares')
                                ->groupBy('choice')
                                ->get();
                        @endphp

                        @if($results->isEmpty())
                            <p><i>Chưa có phiếu bầu nào.</i></p>
                        @else
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Lựa chọn</th>
                                    <th>Tổng cổ phần</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($results as $r)
                                    <tr>
                                        <td>{{ $r->choice }}</td>
                                        <td>{{ number_format($r->total_shares) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
