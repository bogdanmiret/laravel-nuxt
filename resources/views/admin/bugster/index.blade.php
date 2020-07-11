@push('scripts')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@endpush

@extends('layouts.admin')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header">
					<div class="pull-right">
						{{--<a href="{{ route('admin.json_lists.create') }}" class="btn btn-primary" ><i class="fa fa-plus"></i> {{ trans('global.btn.add_json_list') }}</a>--}}
					</div>
				</div>
				<div class="box-body">
					<div id="bug_chart" style="height: 300px;"></div>
					<br>
					<br>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header">
					<div class="pull-right">
						{{--<a href="{{ route('admin.json_lists.create') }}" class="btn btn-primary" ><i class="fa fa-plus"></i> {{ trans('global.btn.add_json_list') }}</a>--}}
					</div>
				</div>
				<div class="box-body">
					<table id="ErrorsTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%" data-delete-message="{{ 'Are you sure?' }}">
						<thead>
						<tr>
							<th>{{trans('global.fld.id')}}</th>
							<th>{{trans('global.fld.method')}}</th>
							<th>{{trans('global.fld.path')}}</th>
							<th>{{trans('global.fld.status_code')}}</th>
							<th>{{trans('global.fld.app_env')}}</th>
							<th>{{trans('global.fld.app_name')}}</th>
							<th>{{trans('global.fld.reported')}}</th>
							<th>{{trans('global.fld.created_at')}}</th>
							<th>{{trans('global.fld.occurrences')}}</th>
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
            "url" : '{!! route('admin.post.bugster') !!}'
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'method', name: 'method'},
            {data: 'path', name: 'path'},
            {data: 'status_code', name: 'status_code'},
            {data: 'app_env', name: 'app_env'},
            {data: 'app_name', name: 'app_name'},
            {data: 'reported', name: 'reported'},
            {data: 'created_at', name: 'created_at'},
            {data: 'occurrences', name: 'occurrences', searchable: false, orderable: false},
            {data: 'action', name: 'action', searchable: false, orderable: false},

        ],
        order: [[7, "desc"]],
	    "columnDefs": [
            {"width": "15%", "targets": 9 }
        ]


    });
</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<script>
    new Morris.Line({
        element: 'bug_chart',
        data: {!! $graph !!}  ,
        xkey: 'date',
        ykeys: ['times'],
        labels: ['Errors']
    });
</script>
@endpush