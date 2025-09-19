@extends('voyager::master')

@section('page_title', 'Mail Templates')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        .table td {
            vertical-align: middle;
        }

        .voyager-search .form-control {
            min-width: 260px;
        }

        .badge + .badge {
            margin-left: 4px;
        }

        .truncate-300 {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 992px) {
            .truncate-300 {
                max-width: 180px;
            }
        }
    </style>
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-mail"></i>
        Mail Templates
    </h1>
    <div class="text-right">
        <a href="{{ route('mail.ui.templates.create') }}" class="btn btn-primary">
            <i class="voyager-plus"></i> <span>New Template</span>
        </a>
    </div>
@stop

@section('content')
    <div class="page-content container-fluid">
        @include('voyager::alerts')

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">

                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="voyager-list"></i> Danh sách</h3>
                    </div>

                    <div class="panel-body">

                        {{-- Search --}}
                        <form method="get" class="form-inline voyager-search" role="search">
                            <div class="form-group">
                                <input class="form-control" type="text" name="q" value="{{ request('q') }}"
                                       placeholder="Search name / code / subject">
                            </div>
                            <button class="btn btn-default" type="submit">
                                <i class="voyager-search"></i> Tìm
                            </button>
                            @if(request()->filled('q'))
                                <a class="btn btn-default" href="{{ route('mail.ui.templates.index') }}">
                                    <i class="voyager-x"></i> Xoá lọc
                                </a>
                            @endif
                        </form>

                        <div class="table-responsive" style="margin-top:15px;">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="width:70px">ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Subject</th>
                                    <th>Placeholders</th>
                                    <th class="text-center" style="width:90px">Enabled</th>
                                    <th class="text-right" style="width:220px">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($items as $it)
                                    <tr>
                                        <td>{{ $it->id }}</td>
                                        <td>{{ $it->name }}</td>
                                        <td class="code">{{ $it->code }}</td>
                                        <td class="truncate-300" title="{{ $it->subject }}">{{ $it->subject }}</td>
                                        <td>
                                            @if(!empty($it->placeholders))
                                                @foreach($it->placeholders as $p)
                                                    <span class="badge badge-info">{{ $p }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($it->enabled)
                                                <span class="label label-success">Yes</span>
                                            @else
                                                <span class="label label-default">No</span>
                                            @endif
                                        </td>
                                        <td class="no-sort no-click text-right" id="bread-actions">
                                            <a class="btn btn-sm btn-warning"
                                               href="{{ route('mail.ui.templates.edit',$it->id) }}">
                                                <i class="voyager-edit"></i> <span
                                                    class="hidden-xs hidden-sm">Edit</span>
                                            </a>
                                            <a class="btn btn-sm btn-success"
                                               href="{{ route('mail.ui.templates.show',$it->id) }}">
                                                <i class="voyager-eye"></i> <span
                                                    class="hidden-xs hidden-sm">View</span>
                                            </a>
                                            <form method="post"
                                                  action="{{ route('mail.ui.templates.destroy',$it->id) }}"
                                                  class="d-inline-block"
                                                  onsubmit="return confirm('Delete this template?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Delete</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            <i class="voyager-megaphone"></i> Không có dữ liệu
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="panel-footer">
                        @include('partials.pagination', ['results' => $items])
                        {{--                        <div class="text-center">--}}
                        {{--                            {{ $items->onEachSide(1)->appends(request()->query())->links('pagination::bootstrap-5') }}--}}
                        {{--                        </div>--}}
                    </div>

                </div> <!-- /.panel -->
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        // chỗ này để dành nếu bạn muốn thêm JS cho bulk action sau này
    </script>
@stop
