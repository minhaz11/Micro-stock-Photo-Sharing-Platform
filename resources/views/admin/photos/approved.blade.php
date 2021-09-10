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
                                <th scope="col">@lang('Approved by')</th>
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
                                 @if ($image->approve && $image->approve->reviewer_id!=null)
                                 <td data-label="@lang('Approved by')"> <a href="{{route('admin.reviewer.detail',$image->approve->reviewer->id)}}">{{$image->approve->reviewer->username}}</a></td>
                                  @else
                                  <td>@lang('Admin')</td>   
                                 @endif
                                <td data-label="@lang('Action')">
                                    <a href="{{route('admin.image.details.all',$image->id)}}" class="icon-btn" data-toggle="tooltip" data-original-title="@lang('Details')">
                                        <i class="las la-desktop text--shadow"></i>
                                    </a>
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
                 {{paginateLinks($images)}}
                </div>
            </div><!-- card end -->
        </div>
       
      

    </div>
@endsection
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