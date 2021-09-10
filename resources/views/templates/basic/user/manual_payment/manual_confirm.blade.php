@extends($activeTemplate.'layouts.user.master')

@section('content')
   
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-deposit ">
                    <div class="card-header card-header-bg base--bg">
                        <h3 class="text-white">{{__($page_title)}}</h3>
                    </div>
                    <div class="card-body  ">
                        <form action="{{ route('user.deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-12 text-center">
                                    <p class="text-center mt-2">@lang('You have requested ') <b
                                            class="text-success">{{ getAmount($data['amount'])  }} {{$general->cur_text}}</b> @lang(', Please pay ')
                                        <b class="text-success">{{getAmount($data['final_amo']) .' '.$data['method_currency'] }}</b> @lang(' for successful payment')
                                    </p>
                                    <h4 class="text-center mb-4">@lang('Please follow the instruction bellow')</h4>

                                   <div class="row justify-content-center">
                                    <p class="my-4 text-center">@php echo  $data->gateway->description @endphp</p>
                                   </div>

                                </div>

                                @if($method->gateway_parameter)

                                    @foreach(json_decode($method->gateway_parameter) as $k => $v)

                                        @if($v->type == "text")
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><strong>{{__(inputTitle($v->field_level))}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                                    <input type="text" class="form-control form-control-lg"
                                                           name="{{$k}}"  value="{{old($k)}}" placeholder="{{__($v->field_level)}}">
                                                </div>
                                            </div>
                                        @elseif($v->type == "textarea")
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><strong>{{__(inputTitle($v->field_level))}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                                        <textarea name="{{$k}}"  class="form-control"  placeholder="{{__($v->field_level)}}" rows="3">{{old($k)}}</textarea>

                                                    </div>
                                                </div>
                                        @elseif($v->type == "file")
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                   
                                                    <div class="photo-upload-area">
                                                        <div class="image-upload">
                                                            <div class="thumb">
                                                                <div class="avatar-preview">
                                                                    <div class="profilePicPreview" style="background-image: url({{ getImage('assets/user/profile/','400x400') }})">
                                                                        <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-edit">
                                                                    <input type="file" class="profilePicUpload" name="{{$k}}" id="profilePicUpload1" accept=".png, .jpg, .jpeg" @if($v->validation == "required") required @endif>
                                                                    <label for="profilePicUpload1" class="border-btn mx-auto d-flex justify-content-center">@lang('Select '){{$v->field_level}}</label>
                                                                    <small class="mt-2 text-facebook">@lang('Supported files:') <b>@lang('jpeg, jpg.')</b> @lang('upload necessary image') </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="cmn-btn   btn-block mt-2 text-center">@lang('Pay Now')</button>
                                    </div>
                                </div>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>


@endsection
@push('style')
<style type="text/css">
    .withdraw-thumbnail{
        max-width: 220px;
        max-height: 220px
    }
</style>
@endpush
@push('script-lib')
<script src="{{asset($activeTemplateTrue.'/js/bootstrap-fileinput.js')}}"></script>
@endpush
@push('style-lib')
<link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/bootstrap-fileinput.css')}}">
@endpush

@push('script')

<script>
    'use strict'
    function proPicURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var preview = $(input).parents('.thumb').find('.profilePicPreview');
            $(preview).css('background-image', 'url(' + e.target.result + ')');
            $(preview).addClass('has-image');
            $(preview).hide();
            $(preview).fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(".profilePicUpload").on('change', function () {
    proPicURL(this);
});

$(".remove-image").on('click', function () {
    $(this).parents(".profilePicPreview").css('background-image', 'none');
    $(this).parents(".profilePicPreview").removeClass('has-image');
    $(this).parents(".thumb").find('input[type=file]').val('');
});

$("form").on("change", ".file-upload-field", function(){ 
  $(this).parent(".file-upload-wrapper").attr("data-text",         $(this).val().replace(/.*(\/|\\)/, '') );
});
</script>

@endpush