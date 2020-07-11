@extends('layouts.admin')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				@if (isset($package->id))
					{!! Form::model($package, ['url' => route('admin.packages.update', [ 'id' => $package->id ] ), 'method' => 'put' ]) !!}
				@else
					{!! Form::open(['url' => route('admin.packages.store' ), 'method' => 'post']) !!}
				@endif
				<div class="box-body">
					@include('admin.packages.partials.features')
					<hr>
					<div class="clearfix text-red"><b>{{ trans('global.required.all_translated_fields') }}</b></div>
					@include('admin.packages.partials.details')
					<hr>
					@include('admin.packages.partials.currencies')

				</div>
				<div class="box-footer">
					{{Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit')])}}
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
@endsection