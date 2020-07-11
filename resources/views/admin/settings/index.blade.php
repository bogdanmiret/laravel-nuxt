@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        {{ trans('admin/settings.category.' . $slug) }}
                    </h3>
                </div>
                <div class="box-body">

                    {!! Form::open(['url' => route('admin.settings.update', 1), 'method' => 'put', 'files' => true]) !!}
                    {!! Form::hidden('slug', $slug) !!}

                    @foreach($all_settings as $settings)
                        @if(!$settings->first()->multi_language)
                            @php $locale = defaultLocale(); @endphp
                            <div style="padding: 10px;">
                                @foreach($settings as $setting)
                                    @include('admin.settings.partials.elements')
                                @endforeach
                            </div>
                        @else
                            <div id="myTabContent" class="tab-content main-tab-content">
                                <div role="tabpanel" class="tab-pane fade in active ">
                                    <div class="tabbable pills">
                                        <ul id="Tab" class="nav nav-pills ">
                                            @if(count($languages) != 1)
                                                @foreach($languages as $key => $locale)
                                                    <li @if($key === defaultLocale()) class="active" @endif><a href="#{{$locale}}" data-toggle="tab">{{ucfirst($locale)}}</a></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                        <div class="tab-content">
                                            @foreach($languages as $locale_key => $locale)
                                                <div class="tab-pane fade {{$locale}} @if($locale_key == defaultLocale()) in active @endif" id="{{$locale}}">
                                                    @foreach($settings as $setting)
                                                        @include('admin.settings.partials.elements')
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <div class="box-footer">
                        {{Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit')])}}
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
