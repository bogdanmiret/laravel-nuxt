@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                {!! Form::open(['url' => route('admin.languages.store'), 'method' => 'POST']) !!}
                <div class="box-body">
                        <div class="form-group">
                            {!! Form::label('name', trans('global.fld.language_folder'), ['class' => 'control-label']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            {!! Form::checkError('name', $errors)  !!}
                        </div>
                </div>
                <div class="box-footer">
                    {!! Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit')]) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection()