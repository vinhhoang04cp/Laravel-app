@extends('voyager::master')

@section('page_title', 'Vote Sessions')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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
        <i class="voyager-check"></i>
        Vote Sessions
    </h1>
    <div class="text-right">
        <a href="{{ route('vote.ui.sessions.create') }}" class="btn btn-primary">
            <i class="voyager-plus"></i> <span>New Session</span>
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
                        <h3 class="panel-title"><i class="voyager-list"></i> Danh sách phiên biểu quyết</h3>
                    </div>

                    <div class="panel-body">

                        {{-- Search --}}
                        <form method="get" class="form-inline voyager-search" role="search">
                            <div class="form-group">
                                <input class="form-control" type="text" name="q" value="{{ request('q') }}"
                                       placeholder="Search title / description">
                            </div>
                            <button class="btn btn-default" type="submit">
                                <i class="voyager-search"></i> Tìm
                            </button>
                            @if(request()->filled('q'))
                                <a class="btn btn-default" href="{{ route('vote.ui.sessions.index') }}">
                                    <i class="voyager-x"></i> Xoá lọc
                                </a>
                            @endif
                        </form>

                        <div class="table-responsive" style="margin-top:15px;">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="width:70px">ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th class="text-center" style="width:100px">Required %</th>
                                    <th class="text-center" style="width:150px">Created</th>
                                    <th class="text-right" style="width:220px">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($sessions as $s)
                                    <tr>
                                        <td>{{ $s->id }}</td>
                                        <td>{{ $s->title }}</td>
                                        <td class="truncate-300" title="{{ $s->description }}">{{ $s->description }}</td>
                                        <td class="text-center">{{ $s->required_percentage }}%</td>
                                        <td class="text-center">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="no-sort no-click text-right" id="bread-actions">
                                            <a class="btn btn-sm btn-warning"
                                               href="{{ route('vote.ui.sessions.edit',$s->id) }}">
                                                <i class="voyager-edit"></i> <span
                                                    class="hidden-xs hidden-sm">Edit</span>
                                            </a>
                                            <a class="btn btn-sm btn-success"
                                               href="{{ route('vote.ui.sessions.show',$s->id) }}">
                                                <i class="voyager-eye"></i> <span
                                                    class="hidden-xs hidden-sm">View</span>
                                            </a>
                                            <form method="post"
                                                  action="{{ route('vote.ui.sessions.destroy',$s->id) }}"
                                                  class="d-inline-block"
                                                  onsubmit="return confirm('Delete this session?')">
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
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="voyager-megaphone"></i> Không có dữ liệu
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="panel-footer">
                        @include('partials.pagination', ['results' => $sessions])
                    </div>

                </div> <!-- /.panel -->
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        // chỗ này để dành nếu anh muốn thêm JS cho bulk action sau này
    </script>
@stop
