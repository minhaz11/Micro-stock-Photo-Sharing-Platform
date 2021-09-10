@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">


        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">

                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold"> @lang('Site Title') </label>
                                    <input class="form-control form-control-lg" type="text" name="sitename"
                                           value="{{$general->sitename}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Currency')</label>
                                    <input class="form-control  form-control-lg" type="text" name="cur_text"
                                           value="{{trans($general->cur_text)}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Currency Symbol') </label>
                                    <input class="form-control  form-control-lg" type="text" name="cur_sym"
                                           value="{{trans($general->cur_sym)}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Per download earn') </label>
                                    <input class="form-control  form-control-lg" type="text" name="per_download"
                                           value="{{trans($general->per_download)}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold"> @lang('Site Base Color')</label>
                                <div class="input-group">
                                <span class="input-group-addon ">
                                    <input type='text' class="form-control  form-control-lg colorPicker"
                                           value="{{$general->base_color}}"/>
                                </span>
                                    <input type="text" class="form-control form-control-lg colorCode" name="base_color" value="{{ $general->base_color }}"/>
                                </div>
                            </div>
   
                                <div class="form-group  col-md-3">
                                    <label class="form-control-label font-weight-bold">@lang('Referral Bonus per subscription purchase (%)') </label>
                                    <input class="form-control  form-control-lg" type="text" name="ref_bonus" value="{{trans($general->ref_bonus)}}">
                                </div>
                          

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('User Registration')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="Enable" data-off="Disabled" name="registration" @if($general->registration) checked @endif>
                            </div>

                            <div class="form-group  col">
                                <label class="form-control-label font-weight-bold"> @lang('Email Verification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="Enable" data-off="Disable" name="ev" @if($general->ev) checked @endif>
                            </div>
                        </div>

                        <div class="row">
                           
                            <div class="form-group col-lg-4 col-sm-6 col-md-4">
                                <label class="form-control-label font-weight-bold">@lang('Email Notification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="Enable" data-off="Disable" name="en" @if($general->en) checked @endif>
                            </div>
                            <div class="form-group col-lg-4 col-sm-6 col-md-4">
                                <label class="form-control-label font-weight-bold"> @lang('SMS Verification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="Enable" data-off="Disable" name="sv" @if($general->sv) checked @endif>
                            </div>
                            <div class="form-group col-lg-4 col-sm-6 col-md-4">
                                <label class="form-control-label font-weight-bold">@lang('SMS Notification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="Enable" data-off="Disable" name="sn" @if($general->sn) checked @endif>
                            </div>
                          
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5 mb-none-30 justify-content-center">

        <div class="col-lg-6 col-md-12 mb-30">

        <div class="card">
                <div class="card-header">
                    @lang('Upload Instructions')
                </div>
        <div class="card-body">
        <form action="{{route('admin.setting.upload')}}" enctype="multipart/form-data" method="POST">
            @csrf
                <div class="form-group ">
                    <label class="form-control-label font-weight-bold"> @lang('Heading') </label>
                    <input class="form-control form-control-lg" type="text" name="heading"
                value="{{$general->instruction->heading}}"    >
                </div>
                <div class="form-group ">
                    <label class="form-control-label font-weight-bold"> @lang('Instruction') </label>
                    <textarea class="form-control form-control-lg"  name="instruction"
                > {{$general->instruction->instruction}} </textarea>
                </div>
               
                <div class="form-group ">
                    <label class="form-control-label font-weight-bold"> @lang('Instruction file') (@lang('Please insert any  .txt file')) </label>
                    <input type="file" class="form-control form-control-lg" type="text" name="txt"
                       accept="text/plain" >
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Update')</button>
                </div>

        </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 mb-30">
            <form action="{{route('admin.watermark')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="image-upload">
                        <div class="thumb">
                            <div class="avatar-preview">
                                <div class="profilePicPreview" style="background-image: url({{ getImage('assets/images/watermark.png') }})">
                                    <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="avatar-edit">
                                <input type="file" class="profilePicUpload" name="watermark" id="profilePicUpload1" accept=".png">
                                <label for="profilePicUpload1" class="bg--success">@lang('Upload watermark') <small>@lang('(.png)(800x400)(transparent)')</small> </label>
                               
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Update')</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush


@push('style')
    <style>
        .sp-replacer {
            padding: 0;
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: 5px 0 0 5px;
            border-right: none;
        }

        .sp-preview {
            width: 100px;
            height: 46px;
            border: 0;
        }

        .sp-preview-inner {
            width: 110px;
        }

        .sp-dd {
            display: none;
        }
    </style>
@endpush

@push('script')
    <script>
        $(function () {
            "use strict";
            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function (color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function () {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });
        });


    </script>
@endpush

