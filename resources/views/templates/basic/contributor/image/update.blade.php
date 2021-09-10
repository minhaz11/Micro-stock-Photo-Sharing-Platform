
@extends($activeTemplate.'layouts.user.master')
@php
    $src = base64_encode(getImage('assets/contributor/watermark/'.$image->image_name));
@endphp
@section('content')
<div class="pb-60">
 
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


<form class="photo-upload-form" method="POST" action="{{route('contributor.image.update',$image->id)}}" enctype="multipart/form-data">
          @csrf
        <div class="row upload-wrapper no-gutters">
          <div class="col-lg-6">
            <div class="photo-upload-area stay">
                <div class="photo-details-thumb">
                <img src="{{imageUrl($image->image_name,'')}}" alt="image">
                      <a href="{{imageUrl($image->image_name)}}" data-rel="lightcase" class="image-view"></a>
                </div>
               
                <div class="mt-4">
                  <button type="button" class="cmn-btn w-100 chng"><i class="las la-exchange-alt"></i> @lang('Change photo')</button>
                </div>
            </div>
            
            <div class="photo-upload-area">
              <div class="file-upload change">
                <div class="image-upload-wrap">
                  <input class="file-upload-input" name="photo" type='file' onchange="readURL(this);" accept="image/*"/>
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
              </div>
              <div class="cssload-loader d-none upmsg">@lang('Updating..')</div>
              <div class="mt-4">
                <button type="button" class="cmn-btn w-100 cancel"><i class="las la-undo-alt"></i> @lang('Cancel')</button>
              </div>
            </div>
         
          </div>
          <div class="col-lg-6">
            <div class="photo-upload-content-area">
              <div class="row bd-highlight">
                <h4 class="title mb-4 mr-auto p-2 bd-highlight">@lang('Image Details')</h4>
                 <a href="{{route('contributor.image.all')}}" class="border-btn mb-4 p-2 bd-highlight mr-3"><i class="las la-backward"></i> @lang('Back')</a>
              </div>
             
              <div class="form-group">
                <label>@lang('Name')</label>
              <input type="text"  name="title" class="form-control" required value="{{$image->title}}">
              </div>
              <div class="form-group">
                <label>@lang('Tags (maximum 10 tags)')</label>
                <select class="select2-auto-tokenize" name="tags[]" multiple="multiple" required>
                  @foreach ($image->tags as $item)
                  <option selected>{{$item}}</option>
                  
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>@lang('Category')</label>
                <select class="niceSelect" name="category" required>
                  
                  @foreach ($categories as $category)
                   <option value="{{$category->id}}" {{$category->name==$image->category->name?'selected':''}}>{{$category->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>@lang('Value')</label>
                <select class="niceSelect" name="value" required>
                  <option value="0" {{$image->premium==0?'selected':''}}>@lang('Free')</option>
                  <option value="1" {{$image->premium==1?'selected':''}}>@lang('Premium')</option>
                </select>
              </div>
           
              <div class="form-group">
                <label>@lang('Description (minimum 100 character, max 191)') <code id="limit" class="ml-2"></code></label>
                <textarea placeholder="Description" name="description" class="form-control" id="description" required>{{$image->description}}</textarea>
              </div>

              <div class="form-file d-none">

              </div>

              <div class="form-group">
                <label>@lang('Zip File')</label>
                <input type="file" name="file" class="form-control" id="file">
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
    $(".select2-auto-tokenize").select2({
         width: 'resolve',
      tags: true,
      tokenSeparators: [',']
    });

    $('form').submit(function(e){
           var fields = $(this).serializeArray();
           $.each(fields,function(i,field){
             if(field.value != ''){
              $('.upmsg').removeClass('d-none')
             }
           })
     })


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


    $(document).on('click','.chng',function(){
      $('.stay').css('display','none')
      $('.change').css('display','block')
      $('#file').attr('required',true)
      $('.form-file').append(` <input type="hidden" value="1" class="form_file" name="form_file">`)
    })

    $(document).on('click','.cancel',function(e){
     
      $('.stay').removeAttr('style').fadeIn('slow')
      $('.change').css('display','none')
      $('#file').removeAttr('required')
      $('.form_file').remove()

    })
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

@push('style')
    
<style>
  .change{
    display: none
  }
</style>

@endpush