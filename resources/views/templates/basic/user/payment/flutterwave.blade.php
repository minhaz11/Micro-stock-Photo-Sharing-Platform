@extends($activeTemplate.'layouts.user.master')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{$deposit->gateway_currency()->methodImage()}}" class="card-img-top" alt=".." class="w-100">
                    </div>
                    <div class="col-md-8">
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
                                <button type="button" class="btn cmn-btn btn-block  btn-custom2 text-center" id="btn-confirm" onClick="payWithRave()">@lang('Pay Now')</button>
                            </li>
                         </ul>
                            
                         

                            <script
                                src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>

                            <script>
                                var btn = document.querySelector("#btn-confirm");
                                btn.setAttribute("type", "button");
                                const API_publicKey = "{{$data->API_publicKey}}";

                                function payWithRave() {
                                    var x = getpaidSetup({
                                        PBFPubKey: API_publicKey,
                                        customer_email: "{{$data->customer_email}}",
                                        amount: "{{$data->amount }}",
                                        customer_phone: "{{$data->customer_phone}}",
                                        currency: "{{$data->currency}}",
                                        txref: "{{$data->txref}}",
                                        onclose: function () {
                                        },
                                        callback: function (response) {
                                            var txref = response.tx.txRef;
                                            var status = response.tx.status;
                                            var chargeResponse = response.tx.chargeResponseCode;
                                            if (chargeResponse == "00" || chargeResponse == "0") {
                                                window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                                            } else {
                                                window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                                            }
                                            // x.close(); // use this to close the modal immediately after payment.
                                        }
                                    });
                                }
                            </script>


                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
