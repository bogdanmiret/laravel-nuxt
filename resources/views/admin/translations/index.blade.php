@extends('layouts.admin')
@push('styles')
    <style>
        .untranslated td:nth-child(2) {
            background-color: #ff6868;
            color: #fff;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-left">
                        @foreach($languages as $language)
                            <a href="{{ route('admin.translations.index', ['lang' => $language]) }}"
                               class="btn @if($language == $lang) btn-success @else btn-primary @endif">{{ ucfirst($language) }}</a>
                        @endforeach
                    </div>
                    <div class="pull-right">
                        <a href="javascript:void(0);" data-href="{{ route('admin.translations.sync') }}"
                           onclick="syncTranslations(this)" class="{{ config('base.btn.sync_languages') }}"><i
                                    class="fa fa-refresh"></i> {{ trans('global.btn.sync_languages', ['language' => ucfirst(defaultLocale())]) }}
                        </a>
                        <a href="{{ route('admin.languages.index') }}" class="btn btn-primary"><i
                                    class="fa fa-search"></i> {{ trans('global.btn.view_languages') }}</a>
                        <a href="{{ route('admin.translations.files') }}" class="btn btn-primary"><i
                                    class="fa fa-search"></i> {{ trans('global.btn.view_trans_files') }}</a>
                    </div>
                </div>
                <div class="box-body">
                    <table id="translationsTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%"
                           data-url="{{ route('admin.translations.dt-update-field') }}" data-folder="{{ $lang }}">
                        <thead>
                        <tr>
                            <th>{{trans('global.fld.location')}}</th>
                            <th>{{trans('global.fld.name')}}</th>
                            <th>{{trans('global.fld.value')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.translations.modals.view')
@endsection
@push('scripts')
    <script>
        let tranlsationsTable = $("#translationsTable").DataTable({
            processing: false,
            serverSide: false,
            aaSorting: [],
            ajax: {
                "url": '{!! route('admin.translations.get-dt', ['folder' => $lang]) !!}'
            },
            columns: [
                {data: 'location', name: 'location'},
                {data: 'name', name: 'name'},
                {data: 'value', name: 'value'},
                {
                    render: function (data, type, row) {
                        let mark = '',
                            btnName = '',
                            title = '',
                            btnClass = 'btn-default';

                        if (row.marked) {
                            mark = 'translated';
                            title = 'Mark as translated';
                            btnName = '<i class="fa fa-check" aria-hidden="true"></i>';
                            btnClass = 'btn-danger'
                        } else {
                            mark = 'un_translated';
                            title = 'Mark as un-translated';
                            btnName = '<i class="fa fa-minus" aria-hidden="true"></i>'
                        }

                        return `
                            <button type="button" title="View translation" class="btn btn-default btn-sm markAsUnTranslated" data-toggle="modal" data-target="#view-trans" data-name="` + row.name + `" data-location="` + row.location + `"><i class="fa fa-eye" aria-hidden="true"></i></button>
                            <button type="button" title="` + title + `" class="btn btn-sm markAsUnTranslated ` + btnClass + `" data-mark="` + mark + `" data-location="` + row.location + `" data-name="` + row.name + `">` + btnName + `</button>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            columnDefs: [
                {"width": "50%", "targets": 2}
            ],
            createdRow: function (row, data, dataIndex) {
                if (data.marked) {
                    $(row).addClass('untranslated');
                }
            }
        });

        jQuery(document).ready(function () {

            $(document).on('click', '.markAsUnTranslated', function () {
                let wrapper = $(this).closest('tr'),
                    mark = $(this).attr('data-mark'),
                    input = wrapper.find('input'),
                    transLocation = $(this).attr('data-location'),
                    transName = $(this).attr('data-name'),
                    transValue = input.val();

                if (mark === 'translated') {
                    $(this).attr('data-mark', 'un_translated');
                    $(this).attr('title', 'Mark as un-translated');
                    $(this).removeClass('btn-danger');
                    $(this).addClass('btn-default');
                    $(wrapper).removeClass('untranslated');
                    $(this).html('<i class="fa fa-minus" aria-hidden="true"></i>');
                    markTranslatable({
                        location: transLocation,
                        name: transName,
                        value: transValue,
                        mark: false
                    });
                } else if (mark === 'un_translated') {
                    $(this).attr('data-mark', 'translated');
                    $(this).attr('title', 'Mark as translated');
                    $(this).removeClass('btn-default');
                    $(this).addClass('btn-danger');
                    $(wrapper).addClass('untranslated');
                    $(this).html('<i class="fa fa-check" aria-hidden="true"></i>');
                    markTranslatable({
                        location: transLocation,
                        name: transName,
                        value: transValue,
                        mark: true
                    });
                }

            });

            $('#view-trans').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget),
                    tr = button.closest('tr'),
                    value = tr.find('input').val(),
                    name = button.attr('data-name'),
                    location = button.attr('data-location'),
                    file = tr.find('input').attr('data-file'),
                    modal = $(this);

                $('#modal-file').val(file);
                $('#modal-name').val(name);
                $('#tr-index').val(tr[0].rowIndex);

                axios.post('/admin/get-en-version',
                    {
                        name: name,
                        location: location
                    })
                    .then(function (response) {
                        $('#en-version').text(response.data);
                    })
                    .catch(function (errors) {
                        console.log(errors);
                    });

                modal.find('#modal-value').val(value);
            });

            $('#saveTrans').click(saveTrans);
        });

        function markTranslatable(data) {
            axios.post('/admin/mark-translation',
                {
                    location: data.location,
                    name: data.name,
                    value: data.value,
                    mark: data.mark
                })
                .then(function (response) {
                })
                .catch(function (errors) {
                    console.log(errors);
                });
        }

        function saveTrans() {
            axios.post('/admin/translations/dt-update-field',
                {
                    file: $('#modal-file').val(),
                    name: $('#modal-name').val(),
                    value: $('#modal-value').val(),
                    folder: $('#translationsTable').attr('data-folder')
                })
                .then(function (response) {
                    $('tr').eq($('#tr-index').val()).find('input').val($('#modal-value').val());
                    $('#view-trans').modal('hide');
                })
                .catch(function (errors) {
                    console.log(errors);
                })
        }
    </script>
@endpush