@extends($activeTemplate.'layouts.user.master')

@section('content')

        <div class="row  justify-content-center">
           
            <div class="col-md-12">

                <div class="card card-deposit text-center">

                    <div class="card-body card-body-deposit">
                        <div class="row">
                            <div class="col-md-4">
                                <li class="list-group-item border-0">
                                    <img width="418px" height="auto" src="{{ $data->gateway_currency()->methodImage() }}"/>
                                </li>
                            </div>
   
                            <div class="col-md-8 ">
                                
                                <ul class="list-group text-center">

                                    <p class="list-group-item">
                                        @lang('Amount'):
                                        <strong>{{getAmount($data->amount)}} </strong> {{$general->cur_text}}
                                    </p>
                                    <p class="list-group-item">
                                        @lang('Charge'):
                                        <strong>{{getAmount($data->charge)}}</strong> {{$general->cur_text}}
                                    </p>


                                    <p class="list-group-item">
                                        @lang('Payable'): <strong> {{getAmount($data->amount + $data->charge)}}</strong> {{$general->cur_text}}
                                    </p>

                                    <p class="list-group-item">
                                        @lang('Conversion Rate'): <strong>1 {{$general->cur_text}} = {{getAmount($data->rate)}}  {{$data->baseCurrency()}}</strong>
                                    </p>


                                    <p class="list-group-item">
                                        @lang('In') {{$data->baseCurrency()}}:
                                        <strong>{{getAmount($data->final_amo)}}</strong>
                                    </p>


                                    @if($data->gateway->crypto==1)
                                        <p class="list-group-item">
                                            @lang('Conversion with')
                                            <b> {{ $data->method_currency }}</b> @lang('and final value will Show on next step')
                                        </p>
                                    @endif
                                </ul>

                                    @if( 1000 >$data->method_code)
                                    <a href="{{route('user.deposit.confirm')}}" class="btn base--bg text-white btn-block py-2 font-weight-bold mt-2">@lang('Pay Now')</a>
                                    @else
                                        <a href="{{route('user.deposit.manual.confirm')}}" class="btn base--bg text-white btn-block py-3 font-weight-bold mt-2">@lang('Pay Now')</a>
                                    @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            
        </div>
  
@stop

@push('style')
    
<style>
    .list-group-item {
    position: relative;
    display: block;
    padding: 1.75rem 0.25rem;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.125);
}
</style>

@endpush

