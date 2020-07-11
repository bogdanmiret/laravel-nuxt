@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <table id="contactFormsTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%" data-delete-message="{{ trans('admin/cf.confirm_delete') }}">
                        <thead>
                        <tr>
                            <th>{{trans('global.fld.username')}}</th>
                            <th>{{trans('global.fld.from_email')}}</th>
                            <th>{{trans('global.fld.subject')}}</th>
                            <th>{{trans('global.fld.created_at')}}</th>
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
    <div class="showContactModal"></div>
@endsection

@push('scripts')
<script>
    $("#contactFormsTable").DataTable({
        processing: true,
        serverSide: true,
        @if(!empty($type))
        ajax: {
            "url": '{!! route('admin.contact-forms.get-dt', ['type' => $type]) !!}'
        },
        @else
        ajax: {
            "url" : '{!! route('admin.contact-forms.get-dt') !!}'
        },
        @endif
        columns: [
            {data: 'username', name: 'username'},
            {data: 'from_email', name: 'from_email'},
            {data: 'subject', name: 'subject'},
            {data: 'created_at', name: 'created_at'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        "columnDefs": [
            {"width": "7%", "targets": 4},
            {"width": "27%", "targets": 5}
        ],
        "order": [[ 3, 'desc' ]]
    });
    $('body').on('click', '.viewContact', function (e) {
        e.preventDefault();
        var contactID;
        var url = $(this).data("href");
        $.get(url, {}, function (data) {
            $('.showContactModal').html(data);
            $('#viewContactForm').modal('show');
        });
    });
</script>
@endpush
