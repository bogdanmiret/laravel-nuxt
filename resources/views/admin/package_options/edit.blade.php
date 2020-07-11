@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                @if (isset($package_option))
                    {!! Form::model($package_option, ['url' => route('admin.package-options.update', [ 'id' => $package_option->id ] ), 'method' => 'put']) !!}
                @else
                    {!! Form::open(['url' => route('admin.package-options.store' ), 'method' => 'post']) !!}
                @endif
                <div class="box-body">
                    <div class="box-body">
                        <div class="clearfix text-red"><b>{{ trans('global.required.all_translated_fields') }}</b></div>
                        <div class="row">
                        @if(isset($package_option))
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('slug') ? ' has-error' : '' }}">
                                    {!! Form::label('slug', trans('global.fld.slug')) !!}
                                    {!! Form::text('slug', null, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                                    {!! Form::checkError('slug', $errors)  !!}
                                </div>
                            </div>
                        @endif

                            <div class="col-md-6">
                            <div class="form-group">
                                <label> {{trans('global.fld.active')}} <span class="symbol required"></span> </label>
                                <input type="checkbox" class="js-switch" @if(isset($package_option->active) && $package_option->active == 1) checked="checked" @endif  value="1" name="active" data-switchery="true">
                            </div>
                        </div>
                    </div>

                    <div class="tabbable pills">
                        <ul id="Tab" class="nav nav-pills">
                            @foreach($languages as $key => $locale)
                                @if($loop->count != 1)
                                    <li @if($key === defaultLocale()) class="active" @endif><a href="#{{$locale}}" data-toggle="tab">{{ucfirst($locale)}}</a></li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @forelse($languages as $key => $locale)
                                <div id="{{$locale}}" class="tab-pane fade @if($key === defaultLocale()) in active @endif">
                                    <div class="form-group {{ $errors->has('translation.' . $locale . '.name') ? ' has-error' : '' }}">
                                        {!! Form::label('name', trans('global.fld.name')) !!}
                                        {!! Form::text('translation['. $locale .'][name]', null, ['class' => 'form-control']) !!}
                                        {!! Form::checkError('translation.' . $locale . '.name', $errors)  !!}
                                    </div>

                                    <div class="form-group {{ $errors->has('translation.' . $locale . '.description') ? ' has-error' : '' }}">
                                        {!! Form::label('description', trans('global.fld.description')) !!}
                                        {!! Form::text('translation[' . $locale . '][description]', null, ['id' => 'description', 'class' => 'form-control']) !!}
                                        {!! Form::checkError('translation.' . $locale . '.description', $errors)  !!}
                                    </div>
                                </div>
                            @empty
                                {{trans('admin.add_language_first')}}
                            @endforelse
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