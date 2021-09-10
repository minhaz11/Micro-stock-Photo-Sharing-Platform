@extends($activeTemplate.'layouts.user.master')

@section('content')
        <div class="row mt-10">
            <div class="col-lg-12">
              <div class="card mb-30 p-0">
                  <div class="card-header">
                    <div class="d-flex flex-wrap">
                      <div class="mr-auto p-2 mt-3"><h6>{{ trans($page_title) }}</h6></div>
                      <div class="p-2">
                          @if ($scope =='approved' )
                          <a href="{{route('contributor.image.upload')}}" target="_blank" class="cmn-btn btn-large"><i class="fas fa-upload mr-2"></i>@lang('Uplaod')</a>
                          @endif
                      </div>
                      <div class="p-2">                             
                      <form class="subscribe-form search__field" action="{{route('contributor.image.search',$scope)}}" method="GET">
                        @csrf
                        <input type="text" name="search" id="subscribe-field" placeholder="{{trans('Search photos')}}" class="form-control" autocomplete="off">
                        <button type="submit" class="subscribe-btn"><i class="las la-search"></i></button>
                      </form>
                      </div>
                  </div>
                  </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table cmn--table white-space-nowrap">
                      <thead>
                        @if ($scope == 'approved')
                        <tr>
                          <th>@lang('Photo')</th>
                          <th>@lang('Upload date')</th>
                          <th>@lang('Downloads')</th>
                          <th>@lang('Views')</th>
                          <th>@lang('Likes')</th>
                          <th>@lang('Status')</th>
                          <th>@lang('Action')</th>
                        </tr>
                        @else
                        <tr>
                          <th>@lang('Photo')</th>
                          <th>@lang('Upload date')</th>
                            @if($scope == 'rejected')
                            <th>@lang('Reason')</th>
                            @endif
                          <th>@lang('Status')</th>
                          <th>@lang('Action')</th>
                        </tr>
                        @endif
                      </thead>
                      <tbody>
                          @forelse ($images as $image)
                          @if ($scope == 'approved')
                          <tr>
                            <td data-label="@lang('Photo')">
                              <div class="photo has-link">
                                <a href="{{route('image.details',[$image->id, slug($image->title)])}}" class="item-link"></a>
                                <div class="thumb"><img src="{{imageUrl($image->image_name,'100x100')}}" alt="image"></div>
                              <p>{{$image->title}}</p>
                              </div>
                            </td>
                            <td data-label="@lang('Upload date')">{{$image->date}}</td>
                            <td data-label="@lang('Downloads')">@lang(number_format_short($image->downloads->count()))</td>
                            <td data-label="@lang('Views')">@lang(number_format_short($image->views->count()))</td>
                            <td data-label="@lang('Likes')"><span class="badge badge-warning">@lang(number_format_short($image->likes->count()))</span></td>
                            <td data-label="@lang('Status')"><span class="badge {{$image->status==1 ?'badge-success':'badge-warning'}}">{{$image->status==1 ? 'active':'deactive'}}</span></td>
                            <td data-label="@lang('Action')">
                              <div>
                                <a href="{{route('contributor.image.edit',$image->id)}}" class="icon-btn bg-primary"><i class="fas fa-pencil-alt text-white"></i></a>
                                <a data-route="{{route('contributor.image.remove',$image->id)}}" class="icon-btn bg-danger delete"><i class="fas fa-trash-alt text-white"></i></a>
                              </div>
                            </td>
                          </tr>

                          @else
                          <tr>
                            <td data-label="@lang('Photo')">
                              <div class="photo has-link">
                                <a href="{{getImage('assets/contributor/watermark/'.$image->image_name)}}" class="item-link" data-rel="lightcase"></a>
                                <div class="thumb"><img src="{{imageUrl($image->image_name,'100x100')}}" alt="image"></div>
                              <p>{{$image->title}}</p>
                              </div>
                            </td>
                            <td data-label="@lang('Upload date')">{{$image->date}}</td>
                            
                            @if ($scope == 'pending')
                            <td data-label="@lang('Status')"><span class="badge badge-warning">@lang('pending')</span></td>
                            
                            @elseif($scope == 'rejected')
                            <td data-label="@lang('Reason')"><button class="icon-btn btn-warning reason" data-toggle="modal" data-target="#reason"  data-reason = "{{json_encode(@$image->reject->reasons)}}" @if(@$image->reject->description!=null) data-des = "{{$image->reject->description}}" @endif><i class="las la-eye text--shadow"></i></button></td>

                            <td data-label="@lang('Status')"><span class="badge badge-danger">@lang('rejected')</span></td>

                            @else
                            <td data-label="@lang('Status')"><span class="badge badge-danger">@lang('banned')</span></td>
                            @endif
                            <td data-label="@lang('Action')">
                              <div>
                                @if($scope == 'rejected')
                                <a href="javascript:void(0)" data-route="{{route('contributor.image.remove',$image->id)}}" class="icon-btn bg-danger delete"><i class="fas fa-trash-alt text-white"></i></a>
                                @endif
                                <a href="javascript:void(0)" class="icon-btn bg-secondary text-white">@lang('N/A')</a>
                              </div>
                            </td>
                          </tr>

                          @endif
                          
                          @empty
                          <tr><td colspan="12" class="text-center">{{$empty_message}}</td></tr>
                          @endforelse
                      </tbody>
                          
                    </table>
                  </div>
                </div>
              </div>
              {{paginateLinks($images,'')}}
            </div>
          </div>

          <div class="modal fade" id="reason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
              <div class="modal-content">
                <div class="modal-header base--bg">
                  <h5 class="modal-title text-white" id="exampleModalLabel">@lang('Reasons')</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body"> 
                    <div class="list-group" id="list"></div>
                    <div class="form-group" id="textarea"> </div>                    
                 </div>
                 <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                </div>
              </div>
            </div>
          </div>


          <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
               <button type="button" class="close ml-auto m-3" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
               </button>
                    <form action="" method="POST">
                        @csrf
                        <div class="modal-body text-center">

                            <i class="las la-exclamation-circle text-danger f-size  mb-15"></i>
                            <h3 class="text--secondary mb-15">@lang('Are You Sure Want to Delete This?')</h3>
    
                        </div>
                    <div class="modal-footer justify-content-center">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('close')</button>
                      <button type="submit"  class="btn btn-danger del">@lang('Delete')</button>
                    </div>
                    
                    </form>
              </div>
            </div>
        </div>
    
@stop

@push('script')
    
    <script>
        'use strict'
        $('.reason').on('click',function(){
        $('#list').empty()
        var reasons = $(this).data('reason')
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


   $('.delete').on('click',function(){
        var route = $(this).data('route')
        var modal = $('#deleteModal');
        modal.find('form').attr('action',route)
        modal.modal('show');


    })
    </script>

@endpush

@push('style')
  
    <style>
      .f-size{
        font-size: 100px;
      }
    </style>

@endpush