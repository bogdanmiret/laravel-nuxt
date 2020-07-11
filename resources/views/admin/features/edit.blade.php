@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                @if (isset($item))

                    {!! Form::model($item, array('url' => route('admin.features.update', [ 'id' => $item['id'] ] ), 'method' => 'put')) !!}
                @else
                    {!! Form::open(array('url' => route('admin.features.store' ), 'method' => 'post')) !!}
                @endif

                <div class="box-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        {!! Form::label('last_name', trans('global.fld.category'), ['class' => 'control-label']) !!}
                    </div>


                        <div class="form-group">
                            {!! Form::label('status', trans('global.fld.status').' 0 | 1', ['class' => 'control-label']) !!}
                            {!! Form::text('status', null, ['class' => 'form-control', 'id' => 'status']) !!}
                            {!! Form::checkError('status', $errors)  !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('importance', trans('global.fld.importance') .' numeric', ['class' => 'control-label']) !!}
                            {!! Form::text('importance', null, ['class' => 'form-control', 'id' => 'importance']) !!}
                            {!! Form::checkError('importance', $errors)  !!}
                        </div>





                    <div class="tabbable pills">
                        <ul id="faqs" class="nav nav-pills ">
                            @if(count($languages) != 1)
                                @foreach($languages as $key => $language)
                                    <li @if($key === config('base.default_locale')) class="active" @endif><a href="#{{$language}}"
                                                                                                             data-toggle="tab">{{ucfirst($language)}}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <div class="tab-content">
                            @foreach($languages as $key => $language)
                                <div class="tab-pane fade {{$language}} @if($key == config('base.default_locale')) in active @endif"
                                     id="{{$language}}">
                                    <div class="form-group">
                                        {!! Form::label('name', trans('global.fld.name'), ['class' => 'control-label']) !!}
                                        {!! Form::text('translation['.$language.'][name]', null, ['class' => 'form-control', 'id' => 'name']) !!}
                                        {!! Form::checkError('translation['.$language.'][name]', $errors)  !!}
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('slug', trans('global.fld.slug'), ['class' => 'control-label']) !!}
                                        {!! Form::text('translation['.$language.'][slug]', null, ['class' => 'form-control', 'id' => 'slug']) !!}
                                        {!! Form::checkError('translation['.$language.'][slug]', $errors)  !!}
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('question', trans('global.fld.question'), ['class' => 'control-label']) !!}
                                        {!! Form::textarea('translation['.$language.'][question]', null, ['class' => 'form-control', 'id' => 'question']) !!}
                                        {!! Form::checkError('translation['.$language.'][question]', $errors)  !!}
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