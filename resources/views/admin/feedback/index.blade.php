@extends('layouts.admin')

@section('content')


	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header">

				</div>
				<div class="box-body">
					<table id="ErrorsTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%" data-delete-message="{{ 'Are you sure?' }}">
						<thead>
						<tr>
							<th>{{trans('global.fld.id')}}</th>
							<th>{{trans('global.fld.model_type')}}</th>
							<th>{{trans('global.fld.passed_profanity')}}</th>
							<th>{{trans('global.fld.status')}}</th>
							<th>{{trans('global.fld.user')}}</th>
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
    $("#ErrorsTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": '{!! route('admin.post.feedback') !!}'
        },
        columns: [
            {data: 'id', name: 'id', searchable: false},
            {data: 'model_type', name: 'model_type', searchable: false},
            {data: 'passed_profanity', name: 'passed_profanity', searchable: false},
            {data: 'status', name: 'status', searchable: false},
            {data: 'user_name', name: 'user_name', sortable: false},
            {data: 'created_at', name: 'created_at', searchable: false},
            {data: 'action', name: 'action', sortable: false, searchable: false},

        ],
        order: [[5, "desc"]],
        "columnDefs": [
            {"width": "15%", "targets": 6}
        ],
		searchDelay: 2000,


    });
</script>
@endpush