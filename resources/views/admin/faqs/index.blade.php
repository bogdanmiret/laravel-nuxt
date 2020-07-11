@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">

                    </h3>
                </div>
                <div class="box-body">
                    <table id="FAQsTable" class="table table-striped table-bordered" cellspacing="0" width="100%" data-delete-message="{{ trans('admin/faqs.confirm_delete') }}">
                        <thead>
                        <tr>
                            <th>{{trans('global.fld.name')}}</th>
                            <th>{{trans('global.fld.category')}}</th>
                            <th>{{trans('global.fld.status')}}</th>
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
        ajax: '{!! route('admin.faqs.get-faqs') !!}',
        columns: [
            {data: 'name', name: 'name'},
            {data: 'category', name: 'category'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        "columnDefs": [
            {"width": "25%", "targets": 3 }
        ]
    });
</script>
@endpush