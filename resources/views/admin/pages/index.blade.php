@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-right">
                        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary" ><i class="fa fa-plus"></i> {{ trans('global.btn.add_page') }}</a>
                    </div>
                </div>
                <div class="box-body">
                    <table id="pagesTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%" data-delete-message="{{ trans('admin/pages.confirm_delete') }}">
                        <thead>
                        <tr>
                            <th>{{trans('global.fld.slug')}}</th>
                            <th>{{trans('global.fld.name')}}</th>
                            <th>{{trans('global.fld.status')}}</th>
                            <th>{{trans('global.fld.action')}}</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    var pagesTable = $("#pagesTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url" : '{!! route('admin.pages.get-dt') !!}'
        },
        columns: [
            {data: 'slug', name: 'slug'},
            {data: 'name', name: 'name'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        "columnDefs": [
            {"width": "25%", "targets": 3}
        ]
    });
</script>
@endpush