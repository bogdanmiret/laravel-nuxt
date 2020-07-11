@extends('layouts.admin')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-body">
					<table id="CompCategoriesTable" class="table table-striped table-bordered" cellspacing="0" width="100%" data-delete-message="{{ trans('admin/restaurant.confirm_delete_category') }}">
						<thead>
						<tr>
							<th>{{trans('global.fld.name')}}</th>
							<th>{{trans('global.fld.description')}}</th>
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
@endsection


@push('scripts')
<script>
    $("#CompCategoriesTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('admin.advertising.get-dt') !!}',
        columns: [
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        "columnDefs": [
            {"width": "25%", "targets": 3 }
        ]
    });
</script>
@endpush