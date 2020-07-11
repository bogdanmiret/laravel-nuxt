<h2>{{ trans('admin/package.currencies.title') }}</h2>
<div class="row clearfix">
	<div class="col-md-12">
		{!! Form::label('discount_active', trans('global.fld.discount_active'), ['class' => 'control-label']) !!}
		{!! Form::checkbox('discount_active', 1, $package->discount_active) !!}
	</div>
	@foreach($package->currencies as $currency_id => $currencies)
		@foreach($currencies as $isocode => $currency)
			<div class="col-md-4">
				<div class="form-group">
					{!! Form::label('currency', $currency['name'], ['class' => 'control-label']) !!}
					<div class="row">
						<div class="col-md-4">
							{!! Form::number('currencies['.$currency['currency_id'].']['.$isocode.'][price]', null, ['class' => 'form-control', 'step' => '0.01']) !!}
						</div>
						<div class="col-md-4">
							{!! Form::number('currencies['.$currency['currency_id'].']['.$isocode.'][discount]', null, ['class' => 'form-control', 'step' => '0.01']) !!}
						</div>
						<div class="col-md-4">
							{!! Form::date('currencies['.$currency['currency_id'].']['.$isocode.'][discount_valid]', null, ['class' => 'form-control', 'step' => '0.01']) !!}
						</div>
					</div>
					{!! Form::checkError('currencies['.$currency['currency_id'].']['.$isocode.'][price]', $errors)  !!}
				</div>
			</div>
		@endforeach
	@endforeach
</div>
