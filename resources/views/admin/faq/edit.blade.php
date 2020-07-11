@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                @if (isset($item))
                    {!! Form::model($item, array('url' => route('admin.faq.update', [ 'id' => $item['id'] ] ), 'method' => 'put')) !!}
                @else
                    {!! Form::open(array('url' => route('admin.faq.store' ), 'method' => 'post')) !!}
                @endif

                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('category', trans('global.fld.category'), ['class' => 'control-label']) !!}
                        {!! Form::select('category', $categories, null, ['class' => 'form-control']) !!}
                        {!! Form::checkError('category', $errors)  !!}
                    </div>
                    <div class="tabbable pills">
                        <ul id="faq" class="nav nav-pills ">
                            @if(count($languages) != 1)
                                @foreach($languages as $key => $language)
                                    <li @if($key === defaultLocale()) class="active" @endif><a href="#{{$language}}" data-toggle="tab">{{ucfirst($language)}}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <div class="tab-content">
                            @foreach($languages as $key => $language)
                                <div class="tab-pane fade {{$language}} @if($key == defaultLocale()) in active @endif"
                                     id="{{$language}}">
                                    <div class="form-group {{ $errors->has('translation.' . $language . '.name') ? ' has-error' : '' }}">
                                        {!! Form::label('last_name', trans('global.fld.name'), ['class' => 'control-label']) !!}
                                        {!! Form::text('translation['.$language.'][name]', null, ['class' => 'form-control', 'id' => 'name']) !!}
                                        {!! Form::checkError('translation['.$language.'][name]', $errors)  !!}
                                    </div>
                                    <div class="form-group {{ $errors->has('translation.' . $language . '.description') ? ' has-error' : '' }}">
                                        {!! Form::label('last_name', trans('global.fld.description'), ['class' => 'control-label']) !!}
                                        {!! Form::textarea('translation['.$language.'][description]', null, ['class' => 'form-control summernote', 'id' => 'faq_desciption']) !!}
                                        {!! Form::checkError('translation['.$language.'][description]', $errors)  !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit')]) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection