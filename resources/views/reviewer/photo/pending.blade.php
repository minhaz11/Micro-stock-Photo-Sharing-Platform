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
                                <th scope="col">@lang('Review Status')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($pendings as $pending)
                            <tr>
                                <td data-label="@lang('Title')">
                                    <div class="user">
                                    <div class="thumb"><img src="{{imageUrl($pending->image->image_name,'100x100')}}" alt="image"></div>
                                    <span class="name">{{$pending->title}}</span>
                                    </div>
                                </td>
                               
                                <td data-label="@lang('Uploadeder')">
                                    {{$pending->user->username}}
                                </td>
                                <td data-label="@lang('Category')">{{$pending->category->name}}</td>
                                <td data-label="@lang('Extension')">{{$pending->extension}}</td>
                                 <td data-label="@lang('Resolution')">{{$pending->resolution}}</td>
                                 
                                 <td data-label="@lang('Review Status')">
                                    @if ($pending->reviewing_status != null)
                                    <span class="text--small badge font-weight-normal badge--primary">@lang('Under Reviewing')</span>
                                    @else
                                    <span class="text--small badge font-weight-normal badge--warning">@lang('Not Reviewed')</span>
                                    @endif
                                </td>
                               
                                <td data-label="@lang('Action')">
                                <a href="{{route('reviewer.photo.action',$pending->id)}}" class="icon-btn"  data-original-title="See Details">
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
                 {{$pendings->links('reviewer.partials.paginate')}}
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection
