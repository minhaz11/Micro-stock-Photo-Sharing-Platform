@extends($activeTemplate.'layouts.user.master')

@section('content')
        <div class="row mt-10">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h6>{{ __($page_title) }}</h6>
                </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table cmn--table white-space-nowrap">
                    <thead>
                      <tr>
                        <th>@lang('SL')</th>
                          <th>@lang('Date')</th>
                        <th>@lang('Percent')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Details')</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($commissions as $k => $commission)
                        <tr>
                            <td data-label="@lang('SL')">{{$commissions->firstItem()+$k}}</td>
                            <td data-label="@lang('Date')">{{showDateTime($commission->created_at,'d M Y')}}</td>
                            <td data-label="@lang('Amount')">{{getAmount($commission->amount)}} {{$general->cur_text}}</td>
                            <td data-label="@lang('Details')">{{$commission->details}}</td>
                          </tr>
                        @empty
                        <tr><td colspan="12" class="text-center">{{$empty_message}}</td></tr>
                        @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
              {{paginateLinks($commissions,'')}}
            </div>
          </div>
        </div>
@stop
