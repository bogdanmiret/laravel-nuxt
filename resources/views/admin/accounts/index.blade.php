@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        @if(!empty($type))
                            {{ trans('admin/users.view_all', ['accounts' => str_plural(getRoleName($type)) ]) }}
                        @else
                            {{ trans('admin/users.view_all_acc') }}
                        @endif
                    </h3>
                </div>
                <div class="box-body">
                    <table id="usersTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%" data-delete-message="{{ trans('admin/users.confirm_delete') }}">
                        <thead>
                        <tr>
                            <th>{{trans('global.fld.created_at')}}</th>
                            <th>{{trans('auth.full_name')}}</th>
                            <th>{{trans('global.fld.email')}}</th>
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
    $("#usersTable").DataTable({
        processing: true,
        serverSide: true,
        @if(!empty($type))
        ajax: {
            "url": '{!! route('admin.accounts.get-dt', ['type' => $type]) !!}'
        },
        @else
        ajax: {
            "url" : '{!! route('admin.accounts.get-dt') !!}'
        },
        @endif
        columns: [
            {data: 'created_at', name: 'created_at'},
            {data: 'full_name', name: 'full_name'},
            {data: 'email', name: 'email'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        "columnDefs": [
            {"width": "25%", "targets": 4}
        ]
    });
</script>
@endpush