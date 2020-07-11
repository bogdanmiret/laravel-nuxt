@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <table id="FeedTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%" data-delete-message="{{ trans('admin/faq.confirm_delete') }}">
                        <thead>
                        <tr>
                            <th>{{trans('global.fld.name')}}</th>
                            <th>{{trans('global.fld.status')}}</th>
                            <th>{{trans('global.fld.categories')}}</th>
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
        $("#FeedTable").DataTable({
            processing: true,
            serverSide: true,
            order: [[ 3, "desc" ]],
            ajax: {
                "url" : '{!! route('admin.feed.get-dt') !!}'
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'status', name: 'status'},
                {data: 'categories', name: 'categories', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "columnDefs": [
                {"width": "20%", "targets": 4 }
            ]
        });
    </script>
@endpush
