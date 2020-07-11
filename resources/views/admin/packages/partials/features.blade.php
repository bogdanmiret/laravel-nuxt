<h2>{{ trans('admin/package.features.title') }}</h2>
<div class="clearfix">
	@foreach($package_options as $key => $package_option)
		<div class="col-lg-3">
			<div class="row">
				<div class="col-lg-6 col-md-10">
					{{ $package_option->name }}
				</div>
				<div class="col-lg-6 col-md-2">
					<label><input type="checkbox" class="minimal" @if(isset($package->id) && isset($package_option->id) && in_array($package_option->id, $package->options->pluck('id')->toArray())) checked="checked" @endif  value="{{ $package_option->id }}" name="packages[{{ $package_option->id }}]"></label>
				</div>
			</div>
		</div>
	@endforeach
</div>