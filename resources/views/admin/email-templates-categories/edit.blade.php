@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                @if(isset($category))
                    {!! Form::model($category, ['url' => route('admin.email-categories.update', [ 'id' => $category['id']]), 'method' => 'put']) !!}
                @else
                    {!! Form::open(['url' => route('admin.email-categories.store')], ['method' => 'post']) !!}
                @endif
                <div class="box-body">
                    <div class="box-body">
                        <div class="form-group">
                            @if(isset($category))
                                {!! Form::label('slug', trans('global.fld.slug')) !!}
                                {!! Form::text('slug', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                {!! Form::checkError('slug', $errors)  !!}
                            @endif
                        </div>
                        <div class="tabbable pills">
                            <ul id="Tab" class="nav nav-pills">
                                @foreach($languages as $key => $locale)
                                    @if($loop->count != 1)
                                        <li @if($key == defaultLocale()) class="active" @endif><a href="#{{$locale}}" data-toggle="tab">{{ucfirst($locale)}}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @forelse($languages as $key => $locale)
                                    <div id="{{$locale}}" class="tab-pane fade @if($key == defaultLocale()) in active @endif">

                                        <div class="form-group {{ $errors->has('translation.' . $locale . '.name') ? ' has-error' : '' }}">
                                            {!! Form::label('name', trans('global.fld.name')) !!}
                                            {!! Form::text('translation['. $locale .'][name]', null, ['class' => 'form-control']) !!}
                                            {!! Form::checkError('translation.' . $locale . '.name', $errors)  !!}
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
