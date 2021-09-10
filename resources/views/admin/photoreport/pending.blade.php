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
                                <th scope="col">@lang('Review Status')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($pendingReports->unique('image_id') as $pending)
                            <tr>
                                <td data-label="@lang('Title')">
                                    <div class="user">
                                    <div class="thumb"><img src="{{imageUrl($image->image_thumb,'100x100')}}" alt="image"></div>
                                    <span class="name">{{$pending->image->title}}</span>
                                    </div>
                                </td>
                               
                                <td data-label="@lang('Uploadeder')">
                                    {{$pending->image->user->username}}
                                </td>
                                <td data-label="@lang('Category')">{{$pending->image->category->name}}</td>
                                <td data-label="@lang('Extension')">{{$pending->image->extension}}</td>
                                 <td data-label="">{{$pending->image->resolution}}</td>
                                 <td data-label="@lang('Review Status')">
                                    @if ($pending->reviewer_id != null ||$pending->admin_id != null)
                                    <span class="text--small badge font-weight-normal badge--primary">@lang('Under Reviewing')</span>
                                    @else
                                    <span class="text--small badge font-weight-normal badge--warning">@lang('Not Reviewed')</span>
                                    @endif
                                </td>
                               
                                <td data-label="@lang('Resolution')">
                                <a href="{{route('admin.reported.action',$pending->image->id)}}" class="icon-btn"  data-original-title="See Details">
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
                 {{paginateLinks($pendingReports)}}
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection
