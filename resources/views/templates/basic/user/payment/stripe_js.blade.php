@extends($activeTemplate.'layouts.user.master')

@push('style')
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .StripeElement {
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid transparent;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }

        .card button {
            padding-left: 0px !important;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{$deposit->gateway_currency()->methodImage()}}" class="card-img-top" alt=".." class="w-100">
                    </div>
                    <div class="col-md-8">
                        <form action="{{$data->url}}" method="{{$data->method}}">

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
                                 <script
                                    src="{{$data->src}}"
                                    class="stripe-button"
                                    @foreach($data->val as $key=> $value)
                                    data-{{$key}}="{{$value}}"
                                    @endforeach
                                   >
                                   </script>
                                </li>
                             </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('script')
    <script>
        'use strict'
        $(document).ready(function () {
            $('button[type="submit"]').addClass("text-center cmn-btn btn-round custom-success text-center btn-lg");
        })
    </script>
@endpush
