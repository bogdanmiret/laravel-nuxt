<h2>{{ trans('admin/package.details.title') }}</h2>

<div class="row">
	@if (isset($package->id))
		<div class="col-md-6">
			<div class="form-group">
				{!! Form::label('slug', trans('global.fld.slug'), ['class' => 'control-label']) !!}
				{!! Form::text('slug', null, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
				{!! Form::checkError('slug', $errors)  !!}
			</div>
		</div>
	@endif
	<div class="{{ isset($package->id) ? "col-md-6" : 'col-md-12' }}">
		<div class="form-group">
			{!! Form::label('active', trans('global.fld.active'), ['class' => 'control-label']) !!}
			{!! Form::checkbox('active', 1, $package->active) !!}
		</div>
	</div>
</div>


<div class="tabbable pills">
	<ul id="Tab" class="nav nav-pills ">
		@if(count(config('languages')) != 1)
			@foreach(config('languages') as $key => $locale)
				<li @if($key === defaultLocale()) class="active" @endif><a href="#{{$locale}}" data-toggle="tab">{{ucfirst($locale)}}</a></li>
			@endforeach
		@endif
	</ul>
	<div class="tab-content">
		@foreach(config('languages') as $locale_key => $locale)
			<div class="tab-pane fade {{$locale}} @if($locale_key == defaultLocale()) in active @endif" id="{{$locale}}">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('name', trans('global.fld.title'), ['class' => 'control-label']) !!}
							{!! Form::text('translation[' . $locale . '][name]', null, ['class' => 'form-control']) !!}
							{!! Form::checkError('translation[' . $locale . '][name]', $errors)  !!}
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('description', trans('global.fld.description'), ['class' => 'control-label']) !!}
							{!! Form::text('translation[' . $locale . '][description]', null, ['class' => 'form-control']) !!}
							{!! Form::checkError('translation[' . $locale . '][description]', $errors)  !!}
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>
</div>









