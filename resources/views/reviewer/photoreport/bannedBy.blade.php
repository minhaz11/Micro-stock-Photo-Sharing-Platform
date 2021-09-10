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
                              
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($images->unique('id') as $img)
                            <tr>
                                <td data-label="@lang('Title')">
                                    <div class="user">
                                    <div class="thumb"><img src="{{imageUrl($image->img,'400x400')}}" alt="image"></div>
                                    <span class="name">{{$img->title}}</span>
                                    </div>
                                </td>
                               
                                <td data-label="@lang('Uploadeder')">
                                    {{$img->user->username}}
                                </td>
                                <td data-label="@lang('Category')">{{$img->category->name}}</td>
                                <td data-label="@lang('Extension')">{{$img->extension}}</td>
                                 <td>{{$img->resolution}}</td>
                               
                               
                                <td data-label="@lang('Resolution')">
                                    <a href="{{getImage('assets/contributor/watermark/'.$img->image_name)}}" class="icon-btn"  data-original-title="See Details" data-rel="lightcase">
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
