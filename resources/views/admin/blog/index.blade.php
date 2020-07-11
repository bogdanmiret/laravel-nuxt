@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-right">
                        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary"><i
                                    class="fa fa-plus"></i> {{ trans('global.btn.add_blog_article') }}</a>
                    </div>
                </div>
                <div class="box-body">
                    <table id="BlogTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%"
                           data-delete-message="{{ trans('admin/faq.confirm_delete') }}">
                        <thead>
                        <tr>
                            <th>{{trans('global.fld.name')}}</th>
                            <th>{{trans('global.fld.status')}}</th>
                            <th>{{trans('global.fld.categories')}}</th>
                            <th>{{trans('global.fld.paid')}}</th>
                            <th>{{trans('global.fld.created_at')}}</th>
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
        $("#BlogTable").DataTable({
            processing: true,
            serverSide: true,
            order: [[3, "desc"]],
            ajax: {
                url: '{!! route('admin.blog.get-dt') !!}'
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'status', name: 'status'},
                {data: 'categories', name: 'categories', orderable: false, searchable: false},
                {data: 'is_paid', name: 'is_paid'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                {
                    render: function (data, type, row) {
                        if (data == 1) {
                            return '<i class="fa fa-check" aria-hidden="true" style="color: #00a65a;"></i>';
                        } else {
                            return '<i class="fa fa-times" aria-hidden="true" style="color: #dd4b39;"></i>';
                        }
                    },
                    targets: 3
                },
                {
                    width: "26%",
                    targets: 5
                }
            ]
        });
    </script>
@endpush