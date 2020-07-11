@push('styles')

<style>
    .north {
        transform:rotate(0deg);
        -ms-transform:rotate(0deg); /* IE 9 */
        -webkit-transform:rotate(0deg); /* Safari and Chrome */
    }
    .west {
        transform:rotate(90deg);
        -ms-transform:rotate(90deg); /* IE 9 */
        -webkit-transform:rotate(90deg); /* Safari and Chrome */
    }
    .south {
        transform:rotate(180deg);
        -ms-transform:rotate(180deg); /* IE 9 */
        -webkit-transform:rotate(180deg); /* Safari and Chrome */

    }
    .east {
        transform:rotate(270deg);
        -ms-transform:rotate(270deg); /* IE 9 */
        -webkit-transform:rotate(270deg); /* Safari and Chrome */
    }
</style>

@endpush

@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            @if(isset($contribution))
                                <b> {{ trans('admin/claim.table.actions') }} :</b>
                                @if($contribution->custom_properties['approved'] == 0)
                                    <a href="{{route('admin.contribution.mark', [$contribution,1, ])}}"
                                       class="btn btn-xs btn-success"><i class="fa fa-check" data-toggle="tooltip"
                                                                         data-placement="top"
                                                                         title=" {{trans('admin/contribution.approve')}}"></i></a>
                                @elseif($contribution->custom_properties['approved'] == 1)
                                    <a href="{{route('admin.contribution.mark', [$contribution,0, ])}}"
                                       class="btn btn-xs btn-warning"><i class="fa fa-cog" data-toggle="tooltip"
                                                                         data-placement="top"
                                                                         title=" {{trans('admin/contribution.disapprove')}}"></i></a>
                                @endif
                                <a href="#" class="btn btn-xs btn-danger"
                                   onclick="deleteContribution('{{ trans('admin/contribution.sure') }}', '{{ route('admin.contribution.destroy', $contribution) }}')"><i
                                            class="fa fa-trash" data-toggle="tooltip" data-placement="top"
                                            title="{{trans('admin/contribution.delete')}}"></i></a>
                            @endif

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h4><b>{{ trans('admin/contribution.information') }}</b></h4>
                                </div>
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-3">
                                                <b>     {{ trans('admin/contribution.show.name') }}</b>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $user->full_name }}
                                            </div>

                                            <div class="col-md-3">
                                                <b> {{ trans('admin/contribution.show.email') }}</b>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $user->email }}
                                            </div>

                                            <div class="col-md-3">
                                                <b>  {{ trans('admin/contribution.show.user_id') }}</b>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $user->id }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="col-md-3">
                                                <b> {{ trans('admin/contribution.show.company_name') }}</b>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $restaurant->name }}
                                            </div>

                                            <div class="col-md-3">
                                                <b>{{ trans('admin/contribution.show.company_city') }}</b>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $restaurant->city->name }}
                                            </div>

                                            <div class="col-md-3">
                                                <b> {{ trans('admin/contribution.show.company_dishes_nr') }}</b>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $restaurant->dishes_count }}
                                            </div>

                                            <div class="col-md-3">
                                                <b>{{ trans('admin/contribution.show.company_id') }}</b>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $restaurant->id }}
                                            </div>


                                        </div>


                                    </div>


                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading"><h4><b>{{ trans('admin/contribution.details') }}</b></h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-3">
                                                <b>  {{ trans('admin/contribution.show.comment') }}</b>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $contribution->custom_properties['comment'] }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="col-md-3">
                                                <b>   {{ trans('admin/contribution.table.created_at') }}</b>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $contribution->created_at }}
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                      <div class="col-md-12 text-center">

                                          <button type="button" class="btn btn-info rotate-button">Rotate <i class="fa fa-repeat"></i> </button>

                                          <button id="save_image" type="button" class="btn btn-danger save-button-ajax">Save <i class="fa fa-floppy-o"></i> </button>

                                      </div>
                                        <div class="col-md-12 text-center">
                                            <div id="success_message"></div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <div class="inside_image" style="overflow:hidden">

                                                <img id="contribution-image" class="north" src="{{$contribution->path}}" style="max-width:100%">

{{--                                                {{ dd($contribution) }}--}}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>


    $( document ).ready(function() {

        var success_block = $('#success_message');
        var angle = 0;


        $('.rotate-button').click(function(){
            var img = $('#contribution-image');
            if(img.hasClass('north')){
                img.attr('class','west');
            }else if(img.hasClass('west')){
                img.attr('class','south');
            }else if(img.hasClass('south')){
                img.attr('class','east');
            }else if(img.hasClass('east')){
                img.attr('class','north');
            }

            angle += 90;

            if(angle == 360)
            {
                angle=0;
            }

        });




        $('.save-button-ajax').on('click', function() {

            if(angle != 0)
            {
                axios.post('{{route('admin.rotate_image')}}', {
                    angle: angle,
                    contribution: '{{ $contribution->id }}'
                })
                        .then(function (response) {
                            angle = 0;

                            success_block.text('Updated');

                            setTimeout(function () {
                                success_block.text('');
                            }, 1000);
                        })
                        .catch(function (error) {
                            console.log(error);
                        });

            }



        });

    });




    function deleteContribution(confirmation_message, delete_address) {
        $(function () {
                    bootbox.confirm({
                        title: '{{ trans('profile.confirmation') }}',
                        message: confirmation_message,

                        buttons: {
                            cancel: {
                                label: '<i class="fa fa-times"></i> Cancel'
                            },
                            confirm: {
                                label: '<i class="fa fa-check"></i> Confirm'
                            }
                        },
                        callback: function (result) {
                            if (result == true) {

                                $.ajax({
                                    type: "DELETE",
                                    url: delete_address,
                                    dataType: "json",
                                    success: function (data) {
                                        if (data.status == "success") {

                                            window.location = data.redirect;
                                        } else {

                                            console.log('Something went wrong');
                                        }
                                    }
                                });


                            }

                        }
                    });
                }
        )
    }
</script>

@endpush
