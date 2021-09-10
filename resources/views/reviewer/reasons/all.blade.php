@extends('reviewer.layouts.app')
@php
    
$user = auth()->guard('reviewer')->user();
@endphp
@section('panel')

    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Created by')</th>
                                <th scope="col">@lang('Reason')</th>
                                <th scope="col">@lang('Created at')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($reasons->unique('reason') as $reason)
                            <tr>
                                @if ($reason->reviewer)
                                    
                               <td data-label="@lang('Created by')">{{$reason->reviewer->username}}</td>
                               @else
                                <td data-label="@lang('Created by')">
                                   @lang('admin')
                                </td>
                                @endif
                           
                                <td data-label="@lang('Reason')">{{$reason->reason}}</td>
                               <td data-label="@lang('Created at')">{{showDateTime($reason->created_at,'d, M, Y')}}</td>
                                <td data-label="@lang('Action')">
                                    @if ($reason->reviewer->username != $user->username)
                                        
                                    <a href="javascript:void(0)" class="icon-btn add" data-toggle="tooltip" title="" data-original-title="add as your reason" data-reason="{{$reason->reason}}">
                                            <i class="las la-plus text--shadow"></i>
                                        </a>
                                     @else
                                     <a href="javascript:void(0)" class="icon-btn btn--secondary" data-toggle="tooltip">
                                        <small>@lang('N\\A')</small>
                                     </a>
                                    @endif
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
               
            </div><!-- card end -->
        </div>
    </div>

    
@endsection

@push('script')
    
<script>
    'use strict';

    $(document).on('click','.add',function(){

    var url = '{{route("reviewer.predefined.reasons.add")}}'
    var data = {
      reason : $(this).data('reason'),
    }
    axios.post(url,data, {
    headers: {
      'Content-Type': 'application/json',
      'key': '12345'
    }
 
  })
  .then(function (response) {
    
    iziToast.success({message:response.data.success, position: "topRight"})

  })
  .catch(function (error) {
    console.log(error);
  })
    })

</script>

@endpush