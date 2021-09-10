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
                        <th>@lang('#SL')</th>
                        <th>@lang('Full Name')</th>
                        <th>@lang('Joined At')</th>
                       
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($referredUsers as $k => $user)
                        <tr>
                            <td data-label="@lang('#SL')">{{$referredUsers->firstItem()+$k}}</td>
                            <td data-label="@lang('Full Name')">{{$user->fullName}}</td>
                            <td data-label="@lang('Joined At')">{{showDateTime($user->created_at,'d M Y')}}</td>
                          </tr>
                        @empty
                        <tr><td colspan="12" class="text-center">{{$empty_message}}</td></tr>
                        @endforelse
                    </tbody>
                        
                  </table>
                </div>
              </div>
              {{paginateLinks($referredUsers,'')}}
            </div>
          </div>
        </div>
@stop
