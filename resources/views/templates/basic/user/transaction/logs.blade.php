@extends($activeTemplate.'layouts.user.master')

@section('content')
   
        <div class="row justify-content-center mt-2">
            <div class="col-lg-12">
                <div class="card card-deposit p-0">
                    <h6 class="card-header">{{$page_title}}</h6>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table cmn--table">
                                <thead class="base--bg text-white">
                                <tr>
                                    <th scope="col">@lang('Transaction ID')</th>
                                    <th scope="col">@lang('Amount')</th>
                                    <th scope="col">@lang('Charge')</th>
                                    
                                    <th scope="col">@lang('Post Balance')</th>
                                    <th scope="col">@lang('Date')</th>
                                    <th scope="col">@lang('Details')</th>
                            
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($trxs as $k=>$data)
                                    <tr>
                                        <td data-label="@lang('Trx')">{{$data->trx}}</td>
                                    
                                        <td data-label="@lang('Amount')">
                                            <strong>{{getAmount($data->amount)}} {{$general->cur_text}}</strong>
                                        </td>
                                        <td data-label="@lang('Charge')" class="text-danger">
                                            {{getAmount($data->charge)}} {{$general->cur_text}}
                                        </td>
                                    
                                        <td data-label="@lang('Post Balance')">
                                            {{getAmount($data->post_balance)}} {{$general->cur_text}}
                                        </td>
                                        <td data-label="@lang('Date')">
                                            {{showDateTime($data->created_at)}}
                                        </td>
                                        <td data-label="@lang('Details')">
                                            {{$data->details}}
                                        </td>
                                    
                                        
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ $empty_message }}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{paginateLinks($trxs)}}
            </div>
        </div>
    </div>



    {{-- Detail MODAL --}}
  

@endsection


