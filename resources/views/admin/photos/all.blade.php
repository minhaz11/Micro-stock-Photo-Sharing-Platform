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
                                <th scope="col">@lang('Photo')</th>
                                <th scope="col">@lang('Uploader')</th>
                                <th scope="col">@lang('Category')</th>
                                <th scope="col">@lang('Date')</th>
                                <th scope="col">@lang('Downloaded')</th>
                                <th scope="col">@lang('Resolution')</th>
                                <th scope="col">@lang('Views')</th>
                                <th scope="col">@lang('Likes')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($images as $image)
                            <tr>
                                <td data-label="@lang('Photo')">
                                    <div class="user">
                                    <div class="thumb"><img src="{{imageUrl($image->image_thumb,'100x100')}}" alt="image"></div>
                                    <span class="name">{{$image->title}}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('Uploader')">{{$image->user->username}}</td>
                                <td data-label="@lang('Category')">{{$image->category->name}}</td>
                                <td data-label="@lang('Date')">{{showDateTime($image->created_at)}}</td>
                                <td data-label="@lang('Downloaded')"><span class="text--small badge font-weight-normal badge--success">{{$image->downloads->count()}}</span></td>
                                <td data-label="@lang('Resolution')">{{$image->resolution}}</td>
                                <td data-label="@lang('Views')"><span class="badge badge-pill bg--primary">{{$image->views->count()}}</span></td>
                                <td data-label="@lang('Likes')"><span class="badge badge-pill bg--danger">{{$image->likes->count()}}</span></td>

                                @if ($image->status==0)
                                <td data-label="@lang('Status')"><span class="badge badge-pill bg--warning">@lang('pending')</span></td>
                                @elseif($image->status==1)
                                <td data-label="@lang('Status')"><span class="badge badge-pill bg--success">@lang('active')</span></td>
                                @elseif($image->status==2)
                                <td data-label="@lang('Status')"><span class="badge badge-pill bg--danger">@lang('banned')</span></td>
                                @elseif($image->status==3)
                                <td data-label="@lang('Status')"><span class="badge badge-pill bg--warning">@lang('rejected')</span></td>
                                @endif
                                <td data-label="@lang('Action')">
                                <a href="{{route('admin.image.details.all',$image->id)}}" class="icon-btn" data-toggle="tooltip" data-original-title="@lang('Details')">
                                        <i class="las la-desktop text--shadow"></i>
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
