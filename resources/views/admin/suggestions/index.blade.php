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
							<th>{{trans('global.fld.company_name')}}</th>
							<th>{{trans('global.fld.user')}}</th>
							<th>{{trans('global.fld.processed')}}</th>
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
            "url": '{!! route('admin.post.suggestions') !!}'
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'company_name', name: 'company_name'},
            {data: 'user_name', name: 'user_name', sortable: false},
            {data: 'processed_label', name: 'processed_label', sortable: false},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', sortable: false, searchable: false},

        ],
        order: [[4, "desc"]],
        "columnDefs": [
            {"width": "15%", "targets": 5}
        ]


    });
</script>
@endpush