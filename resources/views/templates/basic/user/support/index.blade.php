@extends($activeTemplate.'layouts.user.master')

@section('content')

  <section class="pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card p-0">
                    <div class="card-header d-flex justify-content-between"> <h6 class="mt-2">{{ __($page_title) }}</h6>
                        <a href="{{route('ticket.open') }}" class="cmn-btn btn-sm ">
                         <i class="fa fa-plus"></i>   @lang('New Ticket')
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table cmn--table">
                                <thead class="base--bg text-white">
                                <tr>
                                    <th scope="col">@lang('Subject')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Last Reply')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($supports as $key => $support)
                                    <tr>
                                        <td data-label="@lang('Subject')"> <a href="{{ route('ticket.view', $support->ticket) }}" class="font-weight-bold"> [Ticket#{{ $support->ticket }}] {{ $support->subject }} </a></td>
                                        <td data-label="@lang('Status')">
                                            @if($support->status == 0)
                                                <span class="badge badge-success py-2 px-3">@lang('Open')</span>
                                            @elseif($support->status == 1)
                                                <span class="badge badge-primary py-2 px-3">@lang('Answered')</span>
                                            @elseif($support->status == 2)
                                                <span class="badge badge-warning py-2 px-3">@lang('Customer Reply')</span>
                                            @elseif($support->status == 3)
                                                <span class="badge badge-dark py-2 px-3">@lang('Closed')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                                        <td data-label="@lang('Action')" class="d-flex flex-wrap justify-content-center">
                                            <a href="{{ route('ticket.view', $support->ticket) }}" class="icon-btn base--bg text-white">
                                                <i class="fa fa-desktop "></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{paginateLinks($supports)}}
            </div>
        </div>
    </div>
  </section>
@endsection
