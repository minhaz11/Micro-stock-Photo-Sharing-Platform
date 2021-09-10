@extends('admin.layouts.app')

@section('panel')

    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Title')</th>
                                <th scope="col">@lang('Uploadeder')</th>
                                <th scope="col">@lang('Category')</th>
                                <th scope="col">@lang('Extension')</th>
                                <th scope="col">@lang('Resolution')</th>
                                <th scope="col">@lang('Uploaded at')</th>
                                <th scope="col">@lang('Rejected at')</th>
                                <th scope="col">@lang('Rejected by')</th>
                                <th scope="col">@lang('Reasons')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($images as $image)
                           
                            <tr>
                                <td data-label="@lang('Title')">
                                    <div class="user">
                                    <div class="thumb"><img src="{{imageUrl($image->image_thumb,'100x100')}}" alt="image"></div>
                                    <span class="name">{{$image->title}}</span>
                                    </div>
                                </td>
                               
                                <td data-label="@lang('Uploadeder')">
                                   {{$image->user->username}}
                                </td>
                                <td data-label="@lang('Category')">{{$image->category->name}}</td>
                                <td data-label="@lang('Extension')">{{$image->extension}}</td>
                                 <td data-label="@lang('Resolution')">{{$image->resolution}}</td>
                                 <td data-label="@lang('Uploaded at')">{{showDateTime($image->created_at)}}</td>
                                 <td data-label="@lang('Rejected at')">{{showDateTime($image->reject->created_at)}}</td>
                                 @if ($image->reject->reviewer_id != null)
                                 <td data-label="@lang('Rejected by')"> <a href="{{route('admin.reviewer.detail',$image->reject->reviewer_id)}}">{{$image->reject->reviewer->username}}</a></td>
                                  @else
                                  <td>@lang('Admin')</td>   
                                 @endif
                                 <td data-label="@lang('Reasons')"><button class="icon-btn reason" data-toggle="modal" data-target="#reason"  data-reason = "{{json_encode($image->reject->reasons)}}" @if($image->reject->description!=null) data-des = "{{$image->reject->description}}" @endif><i class="las la-eye text--shadow"></i></button></td>
                                <td data-label="@lang('Action')">
                               
                                <a href="{{getImage('assets/contributor/watermark/'.$image->image_name)}}" class="icon-btn"  data-original-title="See Details" data-rel="lightcase">
                                    <i class="las la-image text--shadow"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ $empty_message }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                 {{$images->links('reviewer.partials.paginate')}}
                </div>
            </div><!-- card end -->
        </div>
       
    </div>
    <div class="modal fade" id="reason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                  </div>
             </div>
             <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
            </div>
          </div>
        </div>
      </div>
@endsection

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
    </script>

@endpush
@push('breadcrumb-plugins')
    <form action="" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('title,username')" value="{{$search??''}}" autocomplete="off">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush