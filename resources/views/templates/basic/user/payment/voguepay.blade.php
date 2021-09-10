@extends($activeTemplate.'layouts.user.master')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">

                        <img src="{{$deposit->gateway_currency()->methodImage()}}" class="card-img-top" alt=".."
                             class="w-100">
                    </div>
                    <div class="col-md-8 text-center">
                        <ul class="list-group text-center">
                            <li class="list-group-item mt-5">
                                <h3>@lang('Please Pay') {{getAmount($deposit->final_amo)}} {{$deposit->method_currency}}</h3>
                            </li>
                            <li class="list-group-item">
                                <h3 class="">
                                    @if (session('pricePlan'))
                                    @lang('To Buy '.session('pricePlan')->name.' Subscription ')
                                    @else
                                    @lang('To Get'){{getAmount($deposit->amount)}}  {{$general->cur_text}}
                                    @endif
                                </h3>
                            </li>
                            <li class="list-group-item">
                                <button type="button"
                                class=" mt-4 cmn-btn btn-block btn-round custom-success text-center btn-lg"
                                id="btn-confirm">@lang('Pay Now')</button>
                            </li>
                         </ul>
                       
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection



@push('script')

    <script src="//voguepay.com/js/voguepay.js"></script>
    <script>
        'use strict'
        var closedFunction = function() {
        }
       var successFunction = function(transaction_id) {
            window.location.href = '{{ route(gatewayRedirectUrl()) }}';
        }
       var failedFunction=function(transaction_id) {
            window.location.href = '{{ route(gatewayRedirectUrl()) }}' ;
        }

        function pay(item, price) {
            //Initiate voguepay inline payment
            Voguepay.init({
                v_merchant_id: "{{ $data->v_merchant_id}}",
                total: price,
                notify_url: "{{ $data->notify_url }}",
                cur: "{{$data->cur}}",
                merchant_ref: "{{ $data->merchant_ref }}",
                memo:"{{$data->memo}}",
                recurrent: true,
                frequency: 10,
                developer_code: '5af93ca2913fd',
                store_id:"{{ $data->store_id }}",
                custom: "{{ $data->custom }}",

                closed:closedFunction,
                success:successFunction,
                failed:failedFunction
            });
        }

        $(document).ready(function () {
            $(document).on('click', '#btn-confirm', function (e) {
                e.preventDefault();
                pay('Buy', {{ $data->Buy }});
            });
        });
    </script>
@endpush
