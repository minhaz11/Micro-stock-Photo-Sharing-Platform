@extends('reviewer.layouts.app')

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
                                <th scope="col">@lang('Approved at')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($images as $approved)
                            <tr>
                                <td data-label="@lang('Title')">
                                    <div class="user">
                                    <div class="thumb"><img src="{{imageUrl($approved->image->image_name,'100x100')}}" alt="image"></div>
                                    <span class="name">{{$approved->image->title}}</span>
                                    </div>
                                </td>
                               
                                <td data-label="@lang('Uploadeder')">
                                    {{$approved->image->user->username}}
                                </td>
                                <td data-label="@lang('Category')">{{$approved->image->category->name}}</td>
                                <td data-label="@lang('Extension')">{{$approved->image->extension}}</td>
                                 <td data-label="@lang('Resolution')">{{$approved->image->resolution}}</td>
                                 <td data-label="@lang('Uploaded at')">{{showDateTime($approved->image->created_at)}}</td>
                                 <td data-label="@lang('Approved at')">{{showDateTime($approved->created_at)}}</td>
                                <td data-label="@lang('Action')">
                                <a href="{{getImage('assets/contributor/watermark/'.$approved->image->image_name)}}" class="icon-btn"  data-original-title="@lang('See Details')" data-rel="lightcase" class="image-view">
                                        <i class="las la-eye text--shadow"></i>
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
