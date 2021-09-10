@extends($activeTemplate.'layouts.user.master')

@section('content')

  <div class="">
    <div class="container">
      <div class="row upload-wrapper no-gutters justify-content-center">
        <div class="col-lg-12 text-center">
          <div class="">
            <h2 class="first font-h4 my-3">@lang($gnl->instruction->heading)</h2>
            <p class="text-medium my-2 pl-5 pr-5 pb-3">
               @lang($gnl->instruction->instruction)
            </p>
            <p class="text-medium my-2 font-weight-bold">@lang('Please download this file and include it in your zip')</p>
            <div class="mt-4">
            <a href="{{route('txt.download')}}" class="cmn-btn btn-sm"><i class="fas fa-cloud-download-alt mr-4"></i> @lang('Download Now')</a>
            </div>
        </div> 
        </div>
      </div>
      
    </div>
  </div>
<div class="pt-60 pb-120">
    <div class="container">
       <form class="photo-upload-form" method="POST" action="{{route('contributor.image.store')}}" enctype="multipart/form-data">
          @csrf
        <div class="row upload-wrapper no-gutters">
          <div class="col-lg-6">
            <div class="photo-upload-area">
              <div class="file-upload">
                <div class="image-upload-wrap">
                  <input class="file-upload-input validate" name="photo" type='file' onchange="readURL(this);" accept="image/*"  required/>
                  <div class="drag-text">
                    <p class="title">@lang('Drag and drop a file or select add Image')</p>
                  </div>
                </div>
               
                <div class="file-upload-content">
                  <img class="file-upload-image" src="" alt="your image" />
                  <div class="image-title-wrap mt-4">
                    <button type="button" onclick="removeUpload()" class="remove-image">@lang('Remove')</button>
                  </div>
                </div>
                <button class="border-btn w-100 mt-3" type="button" onclick="$('.file-upload-input').trigger( 'click' )">@lang('Add Image')</button>
                <small class="text-center text-muted my-3">@lang('You can upload maximum 10MB of image')</small>
              </div>
              <div class="cssload-loader d-none upmsg">@lang('Uploading..')</div>
            </div>
          
          </div>
          <div class="col-lg-6 new">
            <div class="photo-upload-content-area">
              <div class="row bd-highlight">
                <h4 class="title mb-4  mr-auto p-2 bd-highlight">@lang('Image Details')</h4>
                <a href="{{route('contributor.image.all')}}" class="border-btn btn-sm mb-4 p-2 bd-highlight mr-3"><i class="fas fa-backward"></i> @lang('Back')</a>
              </div>
              <div class="form-group">
                <label>@lang('Title')</label>
              <input type="text" placeholder="Photo Tile" name="title" class="form-control validate" required value="{{old('title')}}">
              </div>
              <div class="form-group">
                <label>@lang('Tags (maximum 10 tags)')</label>
                <select class="select2-auto-tokenize validate" name="tags[]" multiple="multiple" required>
                  <option>@lang('Natural')</option>
                  <option>@lang('Beauty')</option>
                  <option>@lang('Office')</option>
                  <option>@lang('Corporate')</option>
                  <option>@lang('Business')</option>
                  <option>@lang('Food')</option>
                  <option>@lang('Sports')</option>
                  <option>@lang('Music')</option>
                  <option>@lang('Travel')</option>
                  <option>@lang('Technology')</option>
                </select>
              </div>
              <div class="form-group">
                <label>@lang('Category')</label>
                <select class="niceSelect validate" name="category" required>
                  @foreach ($categories as $category)
                   <option value="{{$category->id}}">{{$category->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>@lang('Value')</label>
                <select class="niceSelect validate" name="value" required>
                  <option value="0">@lang('Free')</option>
                  <option value="1">@lang('Premium')</option>
                </select>
              </div>
            
          
              <div class="form-group">
                <label>@lang('Description (minimum 100 character, max 191) ') <code id="limit" class="ml-2"></code></label>
                <textarea placeholder="Description" name="description" class="form-control validate" id="description" required>{{old('title')}}</textarea>
                
              </div>
              <div class="form-group">
                <label>@lang('Zip File')</label>
                <input type="file" name="file" class="form-control validate" required>
              </div>
              <div class="mt-4">
                <button type="submit" class="cmn-btn w-100 up"><i class="fas fa-cloud-upload-alt mr-4"></i> @lang('Upload Now')</button>
               
              </div>
            </div>
          </div>
        </div>
      </form>
   
    </div>
  </div>
@endsection

@push('script')
<script>

  'use strict'
    // image upload js
    function readURL(input) {
      if (input.files && input.files[0]) {

        var reader = new FileReader();
        
        reader.onload = function(e) {
          $('.image-upload-wrap').hide();

          $('.file-upload-image').attr('src', e.target.result);
          $('.file-upload-content').show();

          $('.image-title').html(input.files[0].name);
        };

        reader.readAsDataURL(input.files[0]);

      } else {
        removeUpload();
      }
    }

    function removeUpload() {
      
      $('.file-upload-input').replaceWith($('.file-upload-input').clone());
      $('.file-upload-content').hide();
      $('.image-upload-wrap').show();
    }
    $('.image-upload-wrap').bind('dragover', function () {
        $('.image-upload-wrap').addClass('image-dropping');
      });
      $('.image-upload-wrap').bind('dragleave', function () {
        $('.image-upload-wrap').removeClass('image-dropping');
     });

     $('form').submit(function(e){
           var fields = $(this).serializeArray();
           $.each(fields,function(i,field){
             if(field.value != ''){
              $('.upmsg').removeClass('d-none')
             }
           })
     })

    // select 2 
    $(".select2-auto-tokenize").select2({
         width: 'resolve',
      tags: true,
      dropdownParent:$('.new'),
      tokenSeparators: [',']
    });


    $('#description').on('keyup paste',function(){
      var text = $(this).val();
      $('#limit').text('Characters '+text.length);
      if(text.length > 191){
        var newStr = text.substring(0, 191);
        $(this).val(newStr);
        $('#limit').text('Description Limit Exceeded');
      }
    })
</script>
@endpush