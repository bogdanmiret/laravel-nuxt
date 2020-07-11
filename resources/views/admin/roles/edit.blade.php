@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="box">
                @if (isset($role))
                    {!! Form::model($role, ['url' => route('admin.roles.update', [ 'id' => $role->id ] ), 'method' => 'put' ]) !!}
                @else
                    {!! Form::open(['url' => route('admin.roles.store' ), 'method' => 'post']) !!}
                @endif
                <div class="box-body">
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        {!! Form::label('name', trans('global.fld.slug')) !!}
                        @if (isset($role))
                        {!! Form::text('name', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                        @else
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        @endif
                        {!! Form::checkError('name', $errors)  !!}
                    </div>

                    <div class="form-group {{ $errors->has('.name') ? ' has-error' : '' }}">
                        {!! Form::label('display_name', trans('global.fld.name')) !!}
                        {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
                        {!! Form::checkError('display_name', $errors)  !!}
                    </div>
                    <h3>{{ trans('admin/roles.permissions') }}</h3>
                    <hr/>
                    @foreach($permissions as $key => $permission)
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-lg-6 col-md-10">
                                    {{ $permission}}
                                </div>
                                <div class="col-lg-6 col-md-2">
                                    <label><input type="checkbox" class="minimal"
                                                  @if(isset($key) && isset($role) && in_array($key, $role->permissions->pluck('id')->toArray())) checked="checked"
                                                  @endif  value="{{ $key }}" name="permissions[{{ $key }}]"></label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="box-footer">
                    {{Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit')])}}
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>

@endsection