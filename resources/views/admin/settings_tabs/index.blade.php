@extends('layouts.admin')

@section('content')
    <div class="nav-tabs-custom" data-example-id="togglable-tabs">
        <ul id="settingsTabs" class="nav nav-tabs" role="tablist">
            @foreach($settings->getCategories() as $category_key => $category)
                <li class="@if($category_key == 0) active @endif "><a href="#{{$category->category}}"
                                                                      id="{{$category->category}}-tab" role="tab"
                                                                      data-toggle="tab"
                                                                      aria-controls="{{$category->category}}"
                                                                      aria-expanded="true">{{trans('admin/settings.category.'.$category->category)}}</a>
                </li>
            @endforeach
        </ul>
        {!! Form::open(['url' => route('admin.settings.update', 1), 'method' => 'put', 'files' => true]) !!}
        <div id="myTabContent" class="tab-content main-tab-content">
            @foreach($settings->getCategories() as $category_key => $category)

                <div role="tabpanel" class="tab-pane fade @if($category_key == 0) in active @endif "
                     id="{{$category->category}}" aria-labelledby="{{$category->category}}-tab">

                    <div class="tabbable pills">
                        <ul id="Tab{{$category_key}}" class="nav nav-pills ">
                            @if(count($languages) != 1)
                                @foreach($languages as $key => $locale)
                                    <li @if($key === defaultLocale()) class="active" @endif><a
                                                href="#{{$category->category}}_{{$locale}}"
                                                data-toggle="tab">{{ucfirst($locale)}}</a></li>
                                @endforeach
                            @endif
                        </ul>
                        <div class="tab-content">
                            @foreach($languages as $locale_key => $locale)
                                <div class="tab-pane fade {{$locale}} @if($locale_key == defaultLocale()) in active @endif"
                                     id="{{$category->category}}_{{$locale}}">

                                    @foreach($settings->getFields($category->category, $locale) as $setting)
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
                                                    @else
                                                        {{Form::$type('translation['. $locale .']['.$setting->key.']', $setting->translateOrDefault($locale)->value, ['class' => 'form-control'])}}
                                                    @endif
                                                        <div class="field-info">{!! $setting->translateOrDefault($locale)->description !!}</div>
                                                </div>

                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="box-footer">
            {{Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit')])}}
        </div>
        {!! Form::close() !!}
    </div>
@endsection
