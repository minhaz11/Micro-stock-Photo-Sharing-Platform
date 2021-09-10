@extends($activeTemplate.'layouts.user.master')

@section('content')

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-deposit">
                   
                    <div class="card-body">
                        <form action="{{route('user.withdraw.submit')}}" method="post" enctype="multipart/form-data">
                            @csrf


                            @if($withdraw->method->user_data)
                            @foreach($withdraw->method->user_data as $k => $v)
                                @if($v->type == "text")
                                    <div class="form-group">
                                        <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                        <input type="text" name="{{$k}}" class="form-control" value="{{old($k)}}" placeholder="{{__($v->field_level)}}" @if($v->validation == "required") required @endif>
                                        @if ($errors->has($k))
                                            <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                        @endif
                                    </div>
                                @elseif($v->type == "textarea")
                                    <div class="form-group">
                                        <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                        <textarea name="{{$k}}"  class="form-control"  placeholder="{{__($v->field_level)}}" rows="3" @if($v->validation == "required") required @endif>{{old($k)}}</textarea>
                                        @if ($errors->has($k))
                                            <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                        @endif
                                    </div>
                                @elseif($v->type == "file")

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
                                    @if ($errors->has($k))
                                    <br>
                                    <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                @endif
                                @endif
                            @endforeach
                            @endif

                        
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn base--bg btn-block btn-lg mt-4 text-center text-white">@lang('Confirm')</button>
                </div>
            </form>
            </div>
           
            <div class="col-md-6">
                <div class="card card-deposit">
                    <h5 class="text-center mt-4 mb-2">@lang('Current Balance') :
                        <strong>{{ getAmount(auth()->user()->balance)}}  {{ $general->cur_text }}</strong></h5>
                    <div class="card-body">
                        <div class="withdraw-details">
                            <span class="font-weight-bold">@lang('Request Amount') :</span>
                            <span class="font-weight-bold pull-right">{{getAmount($withdraw->amount)  }} {{$general->cur_text }}</span>
                        </div>


                        <div class="withdraw-details text-danger">
                            <span class="font-weight-bold">@lang('Withdrawal Charge') :</span>
                            <span class="font-weight-bold pull-right">{{getAmount($withdraw->charge) }} {{$general->cur_text }}</span>
                        </div>


                        <div class="withdraw-details text-info">
                            <span class="font-weight-bold">@lang('After Charge') :</span>
                            <span class="font-weight-bold pull-right">{{getAmount($withdraw->after_charge) }} {{$general->cur_text }}</span>
                        </div>



                        <div class="withdraw-details">
                            <span class="font-weight-bold">@lang('Conversion Rate') : <br>1 {{$general->cur_text }} = </span>
                            <span class="font-weight-bold pull-right">  {{getAmount($withdraw->rate)  }} {{$withdraw->currency }}</span>
                        </div>


                        <div class="withdraw-details text-success">
                            <span class="font-weight-bold">@lang('You Will Get') :</span>
                            <span class="font-weight-bold pull-right">{{getAmount($withdraw->final_amount) }} {{$withdraw->currency }}</span>
                        </div>




                        <div class="form-group mt-5">

                            <label class="font-weight-bold">@lang('Balance Will be') : </label>
                            <div class="input-group">
                                <input type="text" value="{{getAmount($withdraw->user->balance - ($withdraw->amount))}}"  class="form-control form-control-lg" placeholder="@lang('Enter Amount')" required readonly>
                                <div class="input-group-prepend">
                                    <span class="input-group-text ">{{ $general->cur_text }} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
        </div>
   
@endsection
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

@push('style')
    
<style>
.photo-upload-area .image-upload {
    width: 100%;
}

.photo-upload-area {
    background-color: #ffffff;
    padding: 0 30px;
    height: 100%;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    align-items: center;
}
</style>

@endpush