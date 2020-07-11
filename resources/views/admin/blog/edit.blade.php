@extends('layouts.admin')

@push('styles')
    <style>
        @media (min-width: 992px) {

            @if(isset($item)&&isset($item['pic']))
                .iframe-top {
                margin-top: -300px;
            }

            @else
                .iframe-top {
                margin-top: -238px;
            }

        @endif





        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <div class="box-body">
                    @if (isset($item))
                        {!! Form::model($item, ['url' => route('admin.blog.update', [ 'id' => $item['id'] ] ), 'method' => 'PUT', 'files' => true, 'id' => 'blog-form' ]) !!}
                    @else
                        {!! Form::open([ 'url' => route('admin.blog.store' ), 'method' => 'POST', 'files' => true, 'id' => 'blog-form' ]) !!}
                    @endif
                    @csrf

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('categories', trans('global.fld.categories'), ['class' => 'control-label']) !!}
                                {!! Form::select('categories[]', $categories, null, ['class' => 'form-control', 'multiple' => 'multiple', 'id' => 'blogCategories']) !!}
                                {!! Form::checkError('categories', $errors)  !!}
                            </div>

                            @if (isset($item))
                                <a class="{{config('base.btn.edit') }}"
                                   href="{{route('admin.blog.republish', $item['id'])}}">{{ trans('global.btn.republish') }}</a>

                                <div class="form-group {{ $errors->has('slug') ? ' has-error' : '' }}">
                                    {!! Form::label('slug', trans('global.fld.slug'), ['class' => 'control-label']) !!}
                                    {!! Form::text('slug', null, ['class' => 'form-control', 'id' => 'slug']) !!}
                                    {!! Form::checkError('slug', $errors)  !!}
                                </div>
                            @endif

                            @if(isset($item)&&isset($item['pic']))
                                <button data-href="{{ route('admin.blog.delete-picture', $item['id']) }}"
                                        class="btn btn-danger pull-right"
                                        data-delete-message="Do you really want to delete this image?"
                                        onclick="deleteBlogPicture(this)" type="button"><i
                                            class="fa fa-times"
                                            aria-hidden="true"></i>{{trans('global.btn.delete_blog_picture')}}
                                </button>
                            @endif

                            <div class="form-group">
                                {!! Form::label('category', trans('global.fld.picture'), ['class' => 'control-label']) !!}
                                {!! Form::file('pic', ['class' => 'form-control blog-pic-uploader']) !!}
                                {!! Form::checkError('category', $errors)  !!}
                            </div>

                            <div class="image_holder">
                                @if(isset($item) && isset($item['pic']))
                                    <img src="{{ img_url('BlogArticle', $item['pic']) }}" class="img-responsive">
                                @else
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-camera fa-stack-1x"></i>
                                        <i class="fa fa-ban fa-stack-2x text-danger"></i>
                                    </span>
                                @endif
                            </div>

                        </div>

                    </div>


                    <div class="tabbable pills">
                        <ul id="faq" class="nav nav-pills ">
                            @if(count($languages) != 1)
                                @foreach($languages as $key => $language)
                                    <li @if($key === defaultLocale()) class="active" @endif>
                                        <a href="#{{$language}}" data-toggle="tab">{{ucfirst($language)}}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>

                        <div class="tab-content">
                            @foreach($languages as $key => $language)
                                <div class="tab-pane fade {{$language}} @if($key == defaultLocale()) in active @endif"
                                     id="{{$language}}">
                                    <div class="row">
                                        <div class="col-md-3">

                                            <div class="box-body">
                                                @if (!isset($item))
                                                    <div class="form-group">
                                                        {!! Form::label(trans('global.fld.use_this_lang')) !!}
                                                        {!! Form::checkbox('translation['.$language.'][language]', $language, false, ['id' => $key]); !!}
                                                    </div>
                                                @endif
                                                @if (isset($item))
                                                    <div class="form-group">
                                                        {!! Form::label('Delete this language') !!}
                                                        {!! Form::checkbox('translation['.$language.'][delete]', $language, false, ['id' => $key]); !!}
                                                    </div>
                                                @endif
                                                <div class="form-group {{ $errors->has('translation.' . $language . '.name') ? ' has-error' : '' }}">
                                                    {!! Form::label('last_name', trans('global.fld.name'), ['class' => 'control-label']) !!}
                                                    {!! Form::text('translation['.$language.'][name]', null, ['class' => 'form-control', 'id' => 'name']) !!}
                                                    {!! Form::checkError('translation['.$language.'][name]', $errors)  !!}
                                                </div>

                                                <div class="form-group {{ $errors->has('translation.' . $language . '.short_description') ? ' has-error' : '' }}">
                                                    {!! Form::label('short_description', trans('global.fld.short_description'), ['class' => 'control-label']) !!}
                                                    {!! Form::textarea('translation['.$language.'][short_description]', null, ['class' => 'form-control', 'id' => 'name']) !!}
                                                    {!! Form::checkError('translation['.$language.'][short_description]', $errors)  !!}
                                                </div>

                                                <div class="form-group {{ $errors->has('translation.' . $language . '.tags') ? ' has-error' : '' }}">
                                                    {!! Form::label('tags', trans('global.fld.tags'), ['class' => 'control-label']) !!}
                                                    @if(isset($item['translation'][$language]['used_tags']) && !empty(array_first($item['translation'][$language]['used_tags'])))
                                                        {!! Form::select('translation['.$language.'][tags][]', $item['translation'][$language]['used_tags'], $item['translation'][$language]['used_tags'], ['class' => 'form-control blogTags', 'multiple' => 'multiple']) !!}
                                                    @else
                                                        {!! Form::select('translation['.$language.'][tags][]', [], [], ['class' => 'form-control blogTags', 'multiple' => 'multiple']) !!}
                                                    @endif
                                                    {!! Form::checkError('translation['.$language.'][tags]', $errors)  !!}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <iframe src="{{ route('admin.blog.contentbuilder', isset($item) ? [ 'blog' => $item['id'], 'lang' => $language ] : '')}}"
                                                    frameborder="0"
                                                    width="100%" style="height: calc(100vh - 220px); padding: 10px;"
                                                    id="contentbuilder-{{ $language }}" class="iframe-top"></iframe>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            {!! Form::label('paid', 'Paid') !!}
                            {!! Form::checkbox('paid', null, isset($item['is_paid']) && $item['is_paid'] == 1, ['id' => 'paid']); !!}
                        </div>
                    </div>


                    <div class="box-footer">
                        {!! Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit')]) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        $('#blog-form').submit(function () {

                    @foreach ($languages as $language)

            var contentbuilder = $('#contentbuilder-{{ $language }}')[0];

            var documentContent = contentbuilder.contentDocument;

            var contentbuilder_val = documentContent.getElementById('content').value;

            $(this).append('<textarea name="translation[{{ $language }}][description]" style="display:none;">' + contentbuilder_val + '</textarea>');

            @endforeach

                return true;
        });

        var $blogCategories = $("#blogCategories").select2({
            language: '{{ defaultLocale() }}',
            width: '100%',
            class: 'form-control',
            tags: true,
            tokenSeparators: [','],
            ajax: {
                type: 'POST',
                url: "{{ route('admin.blog-categories.get-sel') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        name: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, page) {
                    var results = data.map(function (item) {
                        return {"id": item.id, "text": item.name};
                    });
                    return {
                        results: results
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
        });

        var $blogTags = $(".blogTags").select2({
            language: '{{ defaultLocale() }}',
            width: '100%',
            class: 'form-control',
            tags: true,
            ajax: {
                type: 'POST',
                url: "{{ route('admin.tags.get-sel-tags') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        name: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, page) {
                    var results = data.map(function (item) {
                        return {"id": item.name, "text": item.name};
                    });
                    return {
                        results: results
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
        });


    </script>
@endpush
