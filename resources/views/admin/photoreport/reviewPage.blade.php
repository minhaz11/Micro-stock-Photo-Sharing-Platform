@extends('admin.layouts.app')

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
                            <span class="font-weight-light text-primary">{{$image->title}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Category')
                            <span class="font-weight-light text-primary">{{$image->category->name}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Uploader')
                            <span class="font-weight-light text-primary">{{$image->user->username}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Uploaded at')
                            <span class="font-weight-light text-primary">{{showDateTime($image->created_at)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Resolution')
                            <span class="font-weight-light text-primary">{{$image->resolution}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Extension')
                            <span class="font-weight-light text-primary">{{$image->extension}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Attribution')
                            <span class="font-weight-light text-primary">{{$image->attribution == 0 ? 'Not required':'Required'}}</span>
                        </li>

                        @php
                            $report = \App\Report::whereImageId($image->id)->whereNotNull('reviewer_id');
                            $reportCount = $report->count();
                            $reviewer = $report->first();
                        @endphp

                        @if ( @$reportCount != 0)
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Reviewing By')
                            <span class="font-weight-light text-primary">{{\App\Reviewer::where('id',@$reviewer->reviewer_id)->first()->username}}</span>
                        </li>
                        @else
                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">@lang('Reviewing By')
                            <span class="font-weight-light text-primary">@lang('Admin')</span>
                        </li>
                        @endif
                        
                    </ul>
                    <label class="mt-4 font-weight-bold">@lang('Tags')</label>
                    <select class="select2-auto-tokenize" multiple="multiple" required disabled>
                        @foreach($image->tags as $option)
                        <option selected>{{ $option }}</option>
                         @endforeach
                    </select>
                    <label class="mt-4 font-weight-bold">@lang('Description')</label>
                    <textarea class="form-control"  disabled  cols="30" rows="5">{{$image->description}}</textarea>

                <h5 class="my-2 text-muted text-center list-group-item">@lang('Total Reported :') {{number_format_short($image->reports->count())}}</h5>
             
                <div class="d-flex justify-content-end">
                    <a href="javascript:void(0)"  data-route="{{route('admin.report.remove',$image->id)}}" class="btn btn--primary p-2 mr-3 w-75" data-msg="@lang('Are you sure want to remove all reports?')" onclick="action(this)"><i class="las la-trash"></i> @lang('Remove all reports')</a>

                    <a class="btn btn--danger p-2 w-75" href="javascript:void(0)" data-route="{{route('admin.report.banned',$image->id)}}" data-msg="@lang('Are you sure want to ban this photo?')" onclick="action(this)"><i class="las la-ban"></i> @lang('Ban this photo')</a>
                </div>

           </div>
        </div> <hr>
       
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card b-radius--10 ">
                    <div class="card-body p-0">
                        <div class="table-responsive--md  table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                <tr>
                                    <th scope="col">@lang('Reported By')</th>
                                    <th scope="col">@lang('Reported at')</th>
                                    <th scope="col">@lang('Reasons')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                   @if ($image->reports->count() > 0)
                                    @forelse($image->reports->paginate(15) as $report)
                                    <tr>
                                        <td data-label="@lang('Reported By')">
                                            <div class="user">
                                            <div class="thumb"><img src="{{getImage('assets/user/profile/'.$report->user->image,'100x100')}}" alt="image"></div>
                                            <span class="name">{{$report->user->username}}</span>
                                            </div>
                                        </td>
                                        
                                        <td data-label="@lang('Reported at')">{{showDateTime($report->created_at)}}</td>
                                        <td data-label="@lang('Reasons')"> 
                                                <button class="icon-btn btn--primary reason"   data-toggle="modal" data-target="#exampleModal"
                                                data-reasons = "{{json_encode($report->reason)}}" @if($report->description!=null) data-des = "{{$report->description}}" @endif
                                                ><i class="las la-eye"></i>  @lang('see')</button>
                                        </td>
                                        
    
                                        <td data-label="@lang('Action')">
                                        <a href="javascript:void(0)" data-route="{{route('admin.reported.remove',$report->id)}}" data-msg="@lang('Are you sure want to remove this report?')" class="icon-btn btn--danger"  data-original-title="@lang('remove')" onclick="action(this)">
                                                <i class="las la-trash text--shadow"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td class="text-muted text-center" colspan="100%">@lang('No data')</td>
                                        </tr>
                                    @endforelse
                                   @endif 
                                </tbody>
                            </table><!-- table end -->
                        </div>
                    </div>
                    <div class="card-footer py-4">
                     {{$image->reports->paginate(15)->links('admin.partials.paginate')}}
                    </div>
                </div><!-- card end -->
            </div>
        </div>

    </div>
 
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg--primary">
        <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Reasons')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="list-group" id="list">
              </div>
            <div class="form-group" id="textarea">
                <label for="my-input">@lang('Write in Details')</label>
            </div>
       </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      </div>

    </div>
  </div>
</div>

{{-- action Modal --}}
<div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
       <button type="button" class="close ml-auto m-3" data-dismiss="modal" aria-label="Close">
       <span aria-hidden="true">&times;</span>
       </button>
            <form action="" method="POST">
                @csrf
                <div class="modal-body text-center">
                    <i class="las la-exclamation-circle text--danger display-2 mb-15"></i>
                    <h4 class="text--secondary mb-15 msg"></h4>
                </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
              <button type="submit"  class="btn btn--danger del">@lang('Confirm')</button>
            </div>
            
            </form>
      </div>
    </div>
</div>


@stop

@push('breadcrumb-plugins')
    
<a href="{{route('admin.reported.pending')}}" class="btn btn--primary"><i class="las la-backward"></i> @lang('Go Back')</a>

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
    

  $('.reason').on('click',function(){
    $('#list').empty()
      var reasons = $(this).data('reasons')
      var descr = $(this).data('des')
      var output = ``;
   
      $.each(reasons,function(key,item){
          ++key
           output +=`<li class="list-group-item">${key}. ${item}</li>` 
      })
     
      if(descr != null){
           output +=`
           <label class="mt-3 font-weight-bold" for="my-input">@lang('Other reasons')</label>
           <textarea  class="form-control" id="des" name="details" cols="30" rows="10" disabled>${descr}</textarea>`    
      }


      $('#list').html(output);
     
      
  })
</script>

@endpush

@push('style')

<style>
  #textarea{
    display: none;
  }
</style>

@endpush
