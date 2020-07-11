@extends('layouts.app')

@section('title')
    403
@endsection
@section('content')
    <div class="container">
        <div class="content">



            <div class="caution">
                <h1>403</h1>

                <h2>{{ $exception->getMessage() }}</h2>
                <h3>{!! trans('restaurant.fetch_pf_limit_reached.message', ['contact_link' => '<a href="'.route('trans.contact.index').'" target="_blank">'.trans('global.btn.contact_us_in_sentence').'</a>']) !!}</h3>
            </div><!-- /.page-header -->

        </div><!-- /.content -->
    </div><!-- /.container -->

@endsection