@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="box">
                {!! Form::open(array('url'=>route('admin.translations.update'), 'class'=>'form-vertical ')) !!}
                <div class="box-header">
                    <div class="pull-left">
                        @foreach(getAvLangs() as $language)
                            <a href="{{ route('admin.translations.files', ['folder' => $language]) }}"
                               class="btn @if($language == explode('/', $lang)[0]) btn-success @else btn-primary @endif">{{ ucfirst($language) }}</a>
                        @endforeach
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('admin.languages.index') }}" class="btn btn-primary"><i
                                    class="fa fa-search"></i> {{ trans('global.btn.view_languages') }}</a>
                        <a href="{{ route('admin.translations.index') }}" class="btn btn-primary"><i
                                    class="fa fa-search"></i> {{ trans('global.btn.view_translations') }}</a>
                    </div>
                </div>
                <div class="box-body">
                    <ul class="nav nav-tabs">
                        @if(!empty($back_link))
                            <li>
                                <a href="{{ route('admin.translations.files', ['folder' => $back_link ])}}"> << </a>
                            </li>
                        @endif
                        @foreach($files as $f)
                            @if(File::isDirectory(base_path() . '/resources/lang/' . $lang . '/' . $f))
                                <li @if($file == $f) class="active" @endif >
                                    <a href="{{ route('admin.translations.files') . '?folder='. $lang . '/' . $f}}">{{ str_replace('.php', '', $f)}}</a>
                                </li>
                            @else
                                <li @if($file == $f) class="active" @endif >
                                    <a href="{{ route('admin.translations.files'). '?folder='. $lang . '&file=' . $f }}">{{ str_replace('.php', '', $f)}}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <hr/>

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th> Pharse</th>
                            <th> Translation</th>

                        </tr>
                        </thead>
                        <tbody>
                        @if(is_array($stringLang))
                            @foreach($stringLang as $key => $val)
                                @if(!is_array($val))
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td><input type="text" name="trans[{{ $key }}]" value="{{ $val }}"
                                                   class="form-control"/>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($val as $k=>$v)
                                        @if(!is_array($v))
                                            <tr>
                                                <td>{{ $key }} - {{ $k }}</td>
                                                <td><input type="text" name="trans[{{ $key }}][{{ $k }}]"
                                                           value="{{ $v }}" class="form-control"/>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach($v as $k2=>$v2)
                                                <tr>
                                                    <td>{{ $key }} - {{ $k }} - {{ $k2 }}</td>
                                                    <td><input type="text" name="trans[{{ $key }}][{{ $k }}][{{ $k2 }}]"
                                                               value="{{ $v2 }}" class="form-control"/>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                        </tbody>

                    </table>
                    <input type="hidden" name="lang" value="{{ $lang }}"/>
                    <input type="hidden" name="file" value="{{ $file }}"/>
                </div>

                <div class="box-footer">
                    {!! Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit')]) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>


@endsection