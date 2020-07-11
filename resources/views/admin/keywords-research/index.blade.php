@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right ">
                <a href="{{ route('admin.keywords.create') }}" class="btn btn-primary" ><i class="fa fa-plus"></i> {{ trans('admin/alerts.btn.add', ['name' => 'keyword']) }}</a>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <table id="KeywordsTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%" data-delete-message="{{ trans('admin/alerts.confirm.delete', ['name' => 'Keyword']) }}">
                        <thead>
                        <tr>
                            <th>{{trans('global.fld.slug')}}</th>
                            <th>{{trans('global.fld.type')}}</th>
                            <th>{{trans('global.fld.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $("#KeywordsTable").DataTable({
            processing: true,
            serverSide: true,
            @if (empty($type) || $type === null)
                ajax: {
                    "url" : '{!! route('admin.keywords.dt') !!}'
                },
                    @elseif (isset($type))
                ajax: {
                    "url" : '{!! route('admin.keywords.dt', ['type' => $type]) !!}'
                },
            @endif

            columns: [
                {data: 'slug', name: 'slug'},
                {data: 'type', name: 'type'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "columnDefs": [
                {"width": "20%", "targets": 2 }
            ]
        });
    </script>
@endpush
