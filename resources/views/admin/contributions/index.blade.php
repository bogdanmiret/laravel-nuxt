@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <br>
            <br>
            <div class="box">
                <div class="box-header">

                </div>
                <div class="box-body">
                    <table id="contributionsTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%"
                           data-delete-message="{{ trans('admin/pages.confirm_delete') }}">
                        <thead>
                        <tr>
                            <th>{{ trans('admin/contribution.table.id') }}</th>
                            <th>{{ trans('admin/contribution.table.company-name') }}</th>
                            <th>{{ trans('admin/contribution.table.company-id') }}</th>
                            <th>{{ trans('admin/contribution.table.user-name') }}</th>
                            <th>{{ trans('admin/contribution.table.approved') }}</th>
                            <th>{{ trans('admin/contribution.table.created_at') }}</th>
                            <th>{{ trans('admin/contribution.table.actions') }}</th>


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
    var pagesTable = $("#contributionsTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.contributions.get") }}',
            type: 'post',
            data : { 'pending' :  '{{null !== Request::get("pending") ?  Request::get("pending") : null }}' },
        },
        columns: [

            {data: 'id', name: 'id'},
            {data: 'company_name', name: 'company_name'},
            {data: 'model_id', name: 'model_id'},
            {data: 'user_name', name: 'user_name'},
            {data: 'approved', name: 'approved'},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions', searchable: false, sortable: false}
        ],
        order: [[5, "desc"]],
        searchDelay: 500
    });


    function deleteContribution(confirmation_message, delete_address) {
        $(function () {
                    bootbox.confirm({
                        title: '{{ trans('profile.confirmation') }}',
                        message: confirmation_message,

                        buttons: {
                            cancel: {
                                label: '<i class="fa fa-times"></i> Cancel'
                            },
                            confirm: {
                                label: '<i class="fa fa-check"></i> Confirm'
                            }
                        },
                        callback: function (result) {
                            if (result == true) {

                                $.ajax({
                                    type: "DELETE",
                                    url: delete_address,
                                    dataType: "json",
                                    success: function (data) {
                                        if (data.status == "success") {

                                            window.location = data.redirect;
                                        } else {

                                            console.log('Something went wrong');
                                        }
                                    }
                                });


                            }

                        }
                    });
                }
        )
    }
</script>
@endpush