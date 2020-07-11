@extends('layouts.admin')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header">
					<div class="pull-right">
						<a href="{{ route('admin.email-categories.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('admin/alerts.btn.add', ['name' => 'email category']) }}</a>
					</div>
				</div>
				<div class="box-body">
					<table id="CategoriesTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%"
					       data-delete-message="{{ trans('admin/emails.confirm_delete') }}">
						<thead>
						<tr>
							<th>{{trans('global.fld.id')}}</th>
							<th>{{trans('global.fld.name')}}</th>
							<th>{{trans('global.fld.slug')}}</th>
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
         $("#CategoriesTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": '{!! route('admin.email-categories.get-dt') !!}',
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'slug', name: 'slug'},
                {data: 'action', name: 'action'}
            ],
            "columnDefs": [
                {"width": "15%", "targets": 3}
            ]
        });

	</script>
@endpush
