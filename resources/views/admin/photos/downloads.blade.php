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
                                <th scope="col">@lang('Downloader')</th>
                                <th scope="col">@lang('IP')</th>
                                <th scope="col">@lang('Contributor')</th>
                                <th scope="col">@lang('Image Type')</th>
                                <th scope="col">@lang('Date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($downloads as $dwnld)
                            <tr>
                                <td data-label="@lang('Photo')">
                                    <div class="user">
                                    <div class="thumb"><img src="{{imageUrl($dwnld->image->image_name,'100x100')}}" alt="image"></div>
                                    <span class="name"><a href="{{route('admin.image.details',$dwnld->image->id)}}">{{$dwnld->image->title}}</a></span>
                                    </div>
                                </td>
                                @if ($dwnld->downloader)
                                    
                                <td data-label="@lang('Downloader')"><a href="{{route('admin.users.detail',$dwnld->downloader->id)}}">{{$dwnld->downloader->username}}</a></td>
                                @else
                                    
                                <td data-label="@lang('Downloader')">@lang('Guest')</td>
                                @endif
                                <td data-label="@lang('IP')"><a href="{{route('admin.ip.downloads',$dwnld->ip)}}">{{$dwnld->ip}}</a></td>
                                <td data-label="@lang('Contributor')"><a href="{{route('admin.users.detail',$dwnld->contributor->id)}}">{{$dwnld->contributor->username}}</a></td>
                                
                                <td data-label="@lang('Image Type')"><span class="badge {{$dwnld->premium == 1 ? 'badge--primary':'badge--dark'}} ">{{$dwnld->premium == 1 ? 'premium':'free'}}</span></td>
                                <td data-label="@lang('Date')">{{showDateTime($dwnld->date,'d M Y')}}</td>
                               
                               
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
                 {{paginateLinks($downloads)}}
                </div>
            </div><!-- card end -->
        </div>


    </div>
@endsection
