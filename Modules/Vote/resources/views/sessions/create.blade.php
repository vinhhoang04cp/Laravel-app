@extends('voyager::master')

@section('page_title', 'Tạo Phiên Biểu Quyết')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-check"></i>
        Tạo Phiên Biểu Quyết
    </h1>
    <a href="{{ route('vote.ui.sessions.index') }}" class="btn btn-warning">
        <i class="voyager-angle-left"></i> <span>Quay lại</span>
    </a>
@stop

@section('content')
    <div class="page-content container-fluid">
        @include('voyager::alerts')

        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="voyager-plus"></i> Thông tin Phiên Vote</h3>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('vote.ui.sessions.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="congress_id">Kỳ Đại Hội</label>
                                <select name="congress_id" id="congress_id" class="form-control" required>
                                    <option value="">-- Chọn kỳ đại hội --</option>
                                    @foreach($congresses as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">Tiêu đề</label>
                                <input type="text" id="title" name="title" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea id="description" name="description" rows="4" class="form-control"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="required_percentage">Tỷ lệ cần đạt (%)</label>
                                <input type="number" id="required_percentage" name="required_percentage"
                                       class="form-control" value="51" min="0" max="100">
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="voyager-check"></i> <span>Lưu</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div> <!-- panel -->
            </div>
        </div>
    </div>
@stop
