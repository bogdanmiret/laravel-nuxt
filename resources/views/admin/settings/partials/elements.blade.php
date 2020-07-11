<div class="form-group">
	<label>
		{{trans('admin/settings.'.$setting->key, [], null, $locale)}} @if($setting->type != 'file') <span class="symbol required"></span> @endif
	</label>
	<div class="form-group">
		@php $type = $setting->type; @endphp
		@if($type == 'file')
			{{Form::$type('translation['. $locale .']['.$setting->key.']')}}
		@elseif($type == 'disabled')
			{{Form::text('translation['. $locale .']['.$setting->key.']', $setting->translateOrDefault($locale)->value, ['class' => 'form-control', 'disabled' => 'disabled'])}}
		@elseif($type == 'textarea')
			{{Form::$type('translation['. $locale .']['.$setting->key.']', $setting->translateOrDefault($locale)->value, ['class' => 'form-control'])}}
		@elseif($type == 'wysiwyg')
			{{Form::textarea('translation['. $locale .']['.$setting->key.']', $setting->translateOrDefault($locale)->value, ['class' => 'form-control summernote'])}}
		@elseif($type == 'json')

			{{Form::textarea('translation['. $locale .']['.$setting->key.']', $setting->translateOrDefault($locale)->value, ['class' => 'form-control'])}}
			{{--@php   $json_items = json_decode($setting->value)   @endphp--}}

		{{--{{ dd($json_items) }}--}}
			{{--@foreach($json_items as $json_item)--}}



			{{--@endforeach--}}
		@else
			{{Form::$type('translation['. $locale .']['.$setting->key.']', $setting->translateOrDefault($locale)->value, ['class' => 'form-control'])}}
		@endif
		<div class="field-info">{!! $setting->translateOrDefault($locale)->description !!}</div>
	</div>
</div>