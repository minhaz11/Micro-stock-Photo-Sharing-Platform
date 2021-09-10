@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">


        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                  
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="form-control-label font-weight-bold text-danger">@lang('Important Note*')</label>
                                <textarea class="form-control font-weight-bold" disabled rows="3">@lang('Please Remember, Be very carefull about changing storage or changing FTP host,  Because if you change setting, make sure you copy all image and file directory of uploaded photos to your new FTP or LOCAL storage. Otherwise photos won\'t be shown to the site.   e.g: Change LOCAL To FTP,  then copy all your directory of images ("assets/contributor/watermark" and "assets/contributor/files") to your FTP directory and vice versa.') </textarea>
                                 
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-control-label font-weight-bold">@lang('Select Upload Storage')</label>
                                <select name="select_storage" class="form-control">
                                    <option value="1" {{$general->select_storage == 1?'selected':''}}>@lang('Local Storage')</option>
                                    <option value="2" {{$general->select_storage == 2?'selected':''}}>@lang('FTP Storage')</option>
                                    <option value="3" {{$general->select_storage == 3?'selected':''}}>@lang('AWS Storage')</option>
                                </select>
                            </div>
                        </div>
    

                        <div class="row config">
                            
                            
                        </div>

                        <div class="row aws">
                           
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                   <button type="submit" class="btn btn--primary btn-block">@lang('Update')</button>
                                </div>
                            </div>
                        </div>
                 </form>           
                     
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function () {
            "use strict";
            $('select[name=select_storage]').on('change', function() {
                var val = $(this).val();
                var ftp = `<div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('FTP Hosting Root Access Path')</label>
                                    <input class="form-control  form-control-lg" type="text" name="host_domain" placeholder="@lang('https://yourdomain.com/foldername')"
                                           value="{{@$general->ftp->host_domain}}">
                                    <small class="text-danger">@lang('https://yourdomain.com/foldername')</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold"> @lang('Host') </label>
                                    <input class="form-control form-control-lg" type="text" name="host" placeholder="@lang('Host')"
                                           value="{{@$general->ftp->host}}">
                                </div>
                            </div>

                          
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Username') </label>
                                    <input class="form-control  form-control-lg" type="text" name="username" placeholder="@lang('Username')"
                                           value="{{@$general->ftp->username}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Password') </label>
                                    <input class="form-control  form-control-lg" type="text" name="password" placeholder="@lang('Password')"
                                           value="{{@$general->ftp->password}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Port') </label>
                                    <input class="form-control  form-control-lg" type="text" name="port" placeholder="@lang('Port')"
                                           value="{{@$general->ftp->port}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Upload Root Folder') </label>
                                    <input class="form-control  form-control-lg" type="text" name="root_path" placeholder="@lang('/html_public/something')" value="{{@$general->ftp->root_path}}">
                                </div>
                            </div>`

              
              var aws = ` <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Access Key')</label>
                                    <input class="form-control form-control-lg" type="text" name="key" value="{{@$general->aws->key}}" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Secret Access Key')</label>
                                    <input class="form-control form-control-lg" type="text" name="secret" value="{{@$general->aws->secret}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Region')</label>
                                    <input class="form-control form-control-lg" type="text" name="region" value="{{@$general->aws->region}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Bucket')</label>
                                    <input class="form-control form-control-lg" type="text" name="bucket" value="{{@$general->aws->bucket}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('URL')</label>
                                    <input class="form-control form-control-lg" type="text" name="url" value="{{@$general->aws->url}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Endpoint')</label>
                                    <input class="form-control form-control-lg" type="text" name="endpoint" value="{{@$general->aws->endpoint}}">
                                </div>
                    </div>`
              
                if(val == 1) {
                    $('.config').children().remove();
                    $('.aws').children().remove();
                } else if(val == 2){
                    $('.config').html(ftp);
                    $('.aws').children().remove();
                }
                else if(val == 3){
                    $('.aws').html(aws);
                    $('.config').children().remove();
                }
            }).change();

        });
    </script>
@endpush
