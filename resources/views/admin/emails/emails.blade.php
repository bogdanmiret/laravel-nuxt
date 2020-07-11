@extends('layouts.admin')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header">
					<div class="pull-right">
						<a href="{{ route('admin.emails.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('global.btn.new_email') }}</a>
					</div>
				</div>
				<div class="box-body">
					<table id="EmailsTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%"
					       data-delete-message="{{ trans('admin/emails.confirm_delete') }}">
						<thead>
						<tr>
							<th>{{trans('global.fld.id')}}</th>
							<th>{{trans('global.fld.user')}}</th>
							<th>{{trans('global.fld.from_email')}}</th>
							<th>{{trans('global.fld.to_email')}}</th>
							<th>{{trans('global.fld.subject')}}</th>
							<th>{{trans('global.fld.content')}}</th>
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
        var $articlesTable = $("#EmailsTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": '{!! route('admin.emails.get-dt') !!}',
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'sender_user', name: 'user', orderable: false, searchable: false},
                {data: 'from_email', name: 'from_email'},
                {data: 'to_email', name: 'to_email'},
                {data: 'subject', name: 'subject'},
                {data: 'content', name: 'content'},
                {data: 'action', name: 'action'}
            ],
            "columnDefs": [
                {"width": "30px", "targets": 0},
                {"width": "15%", "targets": 6}
            ]
        });

	</script>
@endpush