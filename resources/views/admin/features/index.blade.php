@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">

                    </h3>
                </div>

                <div class="box-header">
                    <div class="pull-right">
                        <a href="{{route('admin.features.create')}}" class="btn btn-primary" ><i class="fa fa-plus"></i> Create</a>
                    </div>
                </div>
                <div class="box-body">
                    <table id="FAQsTable" class="table table-striped table-bordered" cellspacing="0" width="100%" data-delete-message="{{ trans('admin/landings.confirm_delete') }}">
                        <thead>
                        <tr>
                            <th>{{trans('global.fld.id')}}</th>
                            <th>{{trans('global.fld.importance')}}</th>
                            <th>{{trans('global.fld.status')}}</th>
                            <th>{{trans('global.fld.name')}}</th>
                            <th>{{trans('global.fld.slug')}}</th>
                            <th>{{trans('global.fld.question')}}</th>
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
    <div class="showRoleModal"></div>
@endsection

@push('scripts')
<script>
    $("#FAQsTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('admin.get.features') !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'importance', name: 'importance',orderable: false, searchable: false},
            {data: 'status', name: 'status',orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'slug', name: 'slug'},
            {data: 'question', name: 'question'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        "order": [[ 2, "desc" ]]
//        "columnDefs": [
//            {"width": "25%", "targets": 3 }
//        ]
    });
</script>
@endpush
