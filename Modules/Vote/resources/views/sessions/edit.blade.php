@extends('voyager::master')

@section('page_title', isset($session) ? 'Chỉnh sửa Phiên Biểu Quyết' : 'Tạo Phiên Biểu Quyết')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-check"></i>
        {{ isset($session) ? 'Chỉnh sửa Phiên Biểu Quyết' : 'Tạo Phiên Biểu Quyết' }}
    </h1>
    <a href="{{ route('vote.ui.sessions.index') }}" class="btn btn-warning">
        <i class="voyager-angle-left"></i> <span>Quay lại</span>
    </a>
@stop

@section('content')
    <div class="page-content container-fluid">
        @include('voyager::alerts')

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="voyager-plus"></i>
                            {{ isset($session) ? 'Cập nhật thông tin' : 'Thông tin Phiên Vote' }}
                        </h3>
                    </div>
                    <div class="panel-body">
                        <form action="{{ isset($session) ? route('vote.ui.sessions.update', $session->id) : route('vote.ui.sessions.store') }}"
                              method="POST">
                            @csrf
                            @if(isset($session))
                                @method('PUT')
                            @endif

                            <div class="form-group {{ $errors->has('congress_id') ? 'has-error' : '' }}">
                                <label for="congress_id">Kỳ Đại Hội</label>
                                <select name="congress_id" id="congress_id" class="form-control" required>
                                    <option value="">-- Chọn kỳ đại hội --</option>
                                    @foreach($congresses as $c)
                                        <option value="{{ $c->id }}"
                                            {{ old('congress_id', $session->congress_id ?? '') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('congress_id'))
                                    <span class="help-block text-danger">{{ $errors->first('congress_id') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                <label for="title">Tiêu đề</label>
                                <input type="text" id="title" name="title"
                                       value="{{ old('title', $session->title ?? '') }}"
                                       class="form-control" required>
                                @if($errors->has('title'))
                                    <span class="help-block text-danger">{{ $errors->first('title') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                <label for="description">Mô tả</label>
                                <textarea id="description" name="description" rows="4"
                                          class="form-control">{{ old('description', $session->description ?? '') }}</textarea>
                                @if($errors->has('description'))
                                    <span class="help-block text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('required_percentage') ? 'has-error' : '' }}">
                                <label for="required_percentage">Tỷ lệ cần đạt (%)</label>
                                <input type="number" id="required_percentage" name="required_percentage"
                                       class="form-control"
                                       value="{{ old('required_percentage', $session->required_percentage ?? 51) }}"
                                       min="0" max="100">
                                @if($errors->has('required_percentage'))
                                    <span class="help-block text-danger">{{ $errors->first('required_percentage') }}</span>
                                @endif
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="voyager-check"></i>
                                    <span>{{ isset($session) ? 'Cập nhật' : 'Lưu' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div> <!-- panel -->
            </div>
        </div>
    </div>
@stop
