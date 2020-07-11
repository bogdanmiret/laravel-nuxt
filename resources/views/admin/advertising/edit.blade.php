@extends('layouts.admin')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				@if (isset($ad->id))
					{!! Form::model($ad, ['url' => route('admin.advertising.update', [ 'id' => $ad->id ] ), 'method' => 'put', 'files' => true ]) !!}
				@endif
				<div class="box-body">

					<h2>{{ trans('admin/package.details.title') }}</h2>

					<div class="row">
						@if (isset($ad->id))
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('slug', trans('global.fld.slug'), ['class' => 'control-label']) !!}
									{!! Form::text('slug', null, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
									{!! Form::checkError('slug', $errors)  !!}
								</div>
							</div>
						@endif
						<div class="{{ isset($ad->id) ? "col-md-6" : 'col-md-12' }}">
							<div class="form-group">
								{!! Form::label('status', trans('global.fld.status'), ['class' => 'control-label']) !!}
								{!! Form::checkbox('status', 1, $ad->status) !!}
							</div>
						</div>
						<div class="{{ isset($ad->id) ? "col-md-6" : 'col-md-12' }}">
							<div class="form-group">
								{!! Form::label('copy', trans('global.fld.copy_ads'), ['class' => 'control-label']) !!}
								{!! Form::checkbox('copy', 1, $ad->copy) !!}
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								{!! Form::label('name', trans('global.fld.name'), ['class' => 'control-label']) !!}
								{!! Form::text('name', null, ['class' => 'form-control']) !!}
								{!! Form::checkError('name', $errors)  !!}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								{!! Form::label('description', trans('global.fld.description'), ['class' => 'control-label']) !!}
								{!! Form::text('description', null, ['class' => 'form-control']) !!}
								{!! Form::checkError('description', $errors)  !!}
							</div>
						</div>
					</div>

					<div class="tabbable pills">
						<ul id="Tab" class="nav nav-pills ">
							@foreach($countries as $isocode => $country_name)
								<li @if($loop->first) class="active" @endif><a href="#{{ $isocode }}" data-toggle="tab">{{ $country_name }}</a></li>
							@endforeach
						</ul>



						<div class="tab-content">
							@foreach($countries as $isocode => $country_name)
								<div class="tab-pane fade {{ $isocode }} @if($loop->first) in active @endif" id="{{$isocode}}">
									<hr>
									<div class="row">
										<div class="col-md-6">

											<div class="form-group">
												{!! Form::label('name', trans('global.fld.ad_script'), ['class' => 'control-label']) !!}
												{!! Form::textarea('ads[ad][' . $isocode. ']', null, ['class' => 'form-control']) !!}
												{!! Form::checkError('ads[ad][' . $isocode . ']', $errors)  !!}
											</div>
										</div>

										<div class="col-md-6">
											@foreach($ad->banners->where('type', 'banner')->where('isocode', $isocode) as $banner)
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															{!! Form::label('url', trans('global.fld.ad_url'), ['class' => 'control-label']) !!}
															{!! Form::checkbox('ads[banner]['.$isocode.']['.$banner->slug.'][status]', 1, $banner->status) !!}
															{!! Form::text('ads[banner]['.$isocode.']['.$banner->slug.'][url]', null, ['class' => 'form-control']) !!}
															{!! Form::checkError('ads[banner]['.$isocode.']['.$banner->slug.'][url]', $errors)  !!}
														</div>
													</div>

													<div class="col-md-4">
														<div class="form-group">
															{!! Form::label('banner', trans('global.fld.ad_banner'), ['class' => 'control-label']) !!}
															@if(isset($ad->ads['banner'][$isocode][$banner->slug]['banner']))
																<a href="{{ url($ad->ads['banner'][$isocode][$banner->slug]['banner']) }}" target="_blank"><i class="fa fa-file-image-o"></i></a>
															@endif
															{!! Form::file('ads[banner]['.$isocode.']['.$banner->slug.'][banner]', null, ['class' => 'form-control']) !!}
															{!! Form::checkError('ads[banner]['.$isocode.']['.$banner->slug.'][banner]', $errors)  !!}
														</div>
													</div>
												</div>
											@endforeach

											@for($i=1; $i<=($maximum_ad_banners-$ad->banners->where('type', 'banner')->where('isocode', $isocode)->count()); $i++)
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															{!! Form::label('banner', trans('global.fld.ad_url'), ['class' => 'control-label']) !!}
															{!! Form::checkbox('ads[banner]['.$isocode.'][not_found_'.$i.'][status]', 1, 0) !!}
															{!! Form::text('ads[banner]['.$isocode.'][not_found_'.$i.'][url]', null, ['class' => 'form-control']) !!}
															{!! Form::checkError('ads[banner]['.$isocode.'][not_found_'.$i.'][url]', $errors)  !!}
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															{!! Form::label('banner', trans('global.fld.ad_banner'), ['class' => 'control-label']) !!}
															{!! Form::file('ads[banner]['.$isocode.'][not_found_'.$i.'][banner]', null, ['class' => 'form-control']) !!}
															{!! Form::checkError('ads[banner]['.$isocode.'][not_found_'.$i.'][banner]', $errors)  !!}
														</div>
													</div>
												</div>
											@endfor
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
























				</div>
				<div class="box-footer">
					{{Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit')])}}
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
@endsection