@extends('reviewer.layouts.app')
@php
  $user = auth()->guard('reviewer')->user();
@endphp
@section('panel')

<div class="card">
  
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="photo-upload-area">
                    <div class="photo-details-thumb">
                    <img src="{{imageUrl($image->image_name,'400x400')}}" alt="image">
                    <a href="{{imageUrl($image->image_name,'400x400')}}" data-rel="lightcase" class="image-view"></a>
                    </div>
                
                </div>
           </div>
           <div class="col-md-4">
                    <ul class="list-group">
                        <li class="list-group-item bg--primary text-center font-weight-bold">@lang('Details')</li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Title')
                            <span class="font-weight--dark">{{$image->title}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Category')
                            <span class="font-weight-light text--dark">{{$image->category->name}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Uploader')
                            <span class="font-weight-light text--dark">{{$image->user->username}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Uploaded at')
                            <span class="font-weight-light text--dark">{{showDateTime($image->created_at)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Resolution')
                            <span class="font-weight-light text--dark">{{$image->resolution}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Extension')
                            <span class="font-weight-light text--dark">{{$image->extension}}</span>
                        </li>
                       
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Value')
                          <span class="font-weight-light text--dark">{{$image->premium==1?'Premium':'Free'}}</span>
                         </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Attribution')
                            <span class="font-weight-light text--dark">{{$image->attribution==1?'Required':'Not Required'}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Updated')
                            <span class="font-weight-light text--dark">{{$image->updated == 1?'Yes':'No'}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('File')
                          @if ($image->file)
                          <a href="{{route('reviewer.file.download',$image->id)}}" class="icon-btn"><span class="font-weight-light text-white">@lang('Download')</span></a> 
                          @else
                          <span class="font-weight-light text-primary">@lang('N/A')</span>
                          @endif
                        </li>
                        
                    </ul>

                    <label class="mt-4 font-weight-bold">@lang('Description')</label>
                    <textarea  disabled class="form-control" cols="30" rows="5">{{$image->description}}</textarea>

                    <label class="mt-4 font-weight-bold">@lang('Tags')</label>
                    <select class="select2-auto-tokenize" multiple="multiple" required disabled>
                        @foreach($image->tags as $option)
                        <option selected>{{ $option }}</option>
                         @endforeach
                    </select>


           </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end">
       <a href="javascript:void(0)" data-route="{{route('reviewer.photo.approve',$image->id)}}" data-msg="@lang('Please Confirm To Approve!!')" class="btn btn--success p-2 mr-3 w-75" onclick="action(this)"><i class="las la-check-double"></i> @lang('Approve')</a>
       <button class="btn btn--danger p-2 w-75" data-toggle="modal" data-target="#exampleModal"><i class="las la-ban"></i> @lang('Reject')</button>
    </div>
</div>

<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg--primary">
        <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Reasons of Rejection')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
   
      
      <div class="modal-body">
        <form action="{{route('reviewer.photo.reject')}}" method="POST">
          @csrf
            <input type="hidden" name="image_id" value="{{$image->id}}">
            <input type="hidden" name="user_id" value="{{$image->user->id}}">
            <div class="form-group list-group">

              @foreach ($user->reasons as $key => $item)
              <div class="custom-control custom-checkbox form-check-primary list-group-item">
              <input type="checkbox" name="reason[]" class="custom-control-input" id="customCheck{{$key}}" value="{{$item->reason}}">
                <label class="custom-control-label" for="customCheck{{$key}}">@lang($item->reason)</label>
              </div>
              @endforeach
      
                <div class="custom-control custom-checkbox form-check-primary list-group-item">
                  <input type="checkbox" name="reason[]" class="custom-control-input" id="customCheck25"  >
                  <label class="custom-control-label" for="customCheck25">@lang('Other..')</label>
                </div>
               
              </div>
            <div class="form-group" id="textarea">
                <label for="my-input">@lang('Write in Details')</label>
               <textarea  class="form-control" id="" name="details" cols="30" rows="10"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                <button type="submit" class="btn btn--primary">@lang('Confirm')</button>
              </div>
        </form>
       </div>
     

    </div>
  </div>
</div>

{{-- approv Modal --}}
<div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
     <button type="button" class="close ml-auto m-3" data-dismiss="modal" aria-label="Close">
     <span aria-hidden="true">&times;</span>
     </button>
          <form action="" method="POST">
              @csrf
              <div class="modal-body text-center">
                  <i class="las la-exclamation-circle text--success display-2 mb-15"></i>
                  <h4 class="text--secondary mb-15 msg"></h4>
              </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
            <button type="submit"  class="btn btn--success del">@lang('Confirm')</button>
          </div>
          
        </form>
    </div>
  </div>
</div>


@stop

@push('breadcrumb-plugins')

<a class="btn btn--primary" href="{{route('reviewer.photo.pending')}}"> <i class="las la-backward"></i>@lang('Back')</a>

@endpush

@push('script')
<script>
  'use strict';
  $(document).on('change','#customCheck25',function(){
  
      if ($(this).is(":checked"))
      {
        $('#textarea').slideDown().css('display','block')
      } else{
        $('#textarea').css('display','none')
   
      }
  
  })

  function action(obj) { 
    $(obj).on('click',function(){
        var route = $(this).data('route')
        var msg  = $(this).data('msg')
        var modal = $('#actionModal');
        modal.find('.msg').text(msg)
        modal.find('form').attr('action',route)
        modal.modal('show');


    })
   }
</script>

@endpush

@push('style')

<style>
  #textarea{
    display: none;
  }
</style>

@endpush
