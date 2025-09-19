@extends('voyager::master')

@section('page_title', 'View Mail Template')

@section('content')
    <div class="page-content container-fluid">
        @include('voyager::alerts')

        <div class="panel panel-bordered">
            <div class="panel-header clearfix">
                <h3 class="panel-title pull-left" style="margin-top: 7px;">
                    Template #{{ $item->id }}
                </h3>
                <div class="pull-right">
                    @can('edit_mail_templates')
                        <a class="btn btn-warning" href="{{ route('mail.ui.templates.edit',$item->id) }}">
                            <i class="voyager-edit"></i> Edit
                        </a>
                    @endcan
                    <a class="btn btn-default" href="{{ route('mail.ui.templates.index') }}">
                        <i class="voyager-list"></i> Back
                    </a>
                </div>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <strong>Name:</strong>
                        <div>{{ $item->name }}</div>
                    </div>

                    <div class="form-group col-md-6">
                        <strong>Code:</strong>
                        <div><code>{{ $item->code }}</code></div>
                    </div>

                    <div class="form-group col-md-12">
                        <strong>Subject:</strong>
                        <div>{{ $item->subject }}</div>
                    </div>

                    <div class="form-group col-md-12">
                        <strong>Placeholders:</strong>
                        <div>
                            @php $phs = is_array($item->placeholders ?? null) ? $item->placeholders : []; @endphp
                            @if(count($phs))
                                @foreach($phs as $p)
                                    <span class="label label-default" style="margin-right:4px;">{{ $p }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <strong>Body:</strong>
                        <div class="well" style="background:#fff;">
                            {!! $item->body !!}
                        </div>
                        <p class="help-block">
                            Các biến có thể dùng: <code>@{{ $name }}</code>, <code>@{{ $phone }}</code>, <code>@{{ $address }}</code> …
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
