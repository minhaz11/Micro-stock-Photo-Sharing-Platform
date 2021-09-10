@extends($activeTemplate.'layouts.user.master')

@section('content')
 
<form class="photo-upload-form" method="POST" action="{{route('user.profile.update')}}" enctype="multipart/form-data">
          @csrf
        <div class="row upload-wrapper no-gutters  pt-60 pb-60">
          <div class="col-md-6">
            <div class="photo-upload-area">
                <div class="image-upload">
                    <div class="thumb">
                        <div class="avatar-preview">
                            <div class="profilePicPreview" style="background-image: url({{ getImage('assets/user/profile/'. auth()->user()->image,'400x400') }})">
                                <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="avatar-edit">
                            <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                            <label for="profilePicUpload1" class="cmn-btn mx-auto d-flex justify-content-center">@lang('Upload Image')</label>
                            <small class="mt-2 text-facebook">@lang('Supported files:') <b>@lang('jpeg, jpg.')</b> @lang('Image will be resized into 400x400px') </small>
                        </div>
                    </div>
                </div>
            </div>

          </div>
      
          <div class="col-md-6">
            <div class="photo-upload-content-area mt-md-0">
              <h4 class="title mb-4">@lang('Profile Details')</h4>
              <div class="form-group">
                <label>@lang('Name')</label>
              <input type="text"  name="fname" class="form-control" required value="{{$profile->firstname}}" required>
              </div>
              <div class="form-group">
                <label>@lang('Name')</label>
              <input type="text"  name="lname" class="form-control" required value="{{$profile->lastname}}" required>
              </div>
              <div class="form-group">
                <label>@lang('Email')</label>
              <input type="email"  class="form-control" required value="{{$profile->email}}" disabled>
              </div>
              <div class="form-group">
                <label>@lang('Mobile')</label>
              <input type="text"   class="form-control" required value="{{$profile->mobile}}" disabled>
              </div>
            
              <div class="form-group">
                <label>@lang('City')</label>
              <input type="text"  name="city" class="form-control" required value="{{@$profile->address->city}}" required>
              </div>
             
              <div class="mt-4">
                <button type="submit" class="cmn-btn w-100"><i class="fas fa-cloud-upload-alt mr-4"></i> @lang('Update')</button>
              </div>
            </div>
          </div>
        </div>
      </form>
   
   
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
  $(this).parent(".file-upload-wrapper").attr("data-text", $(this).val().replace(/.*(\/|\\)/, '') );
});

</script>
@endpush