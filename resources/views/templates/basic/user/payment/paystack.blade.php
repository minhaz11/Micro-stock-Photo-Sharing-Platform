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
                        <form action="{{ route('ipn.'.$deposit->gateway->alias) }}" method="POST" class="text-center">

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
                                    <button type="button" class=" mt-4 base--bg btn-block text-white btn-round custom-success text-center btn-lg" id="btn-confirm">@lang('Pay Now')</button>
                                </li>
                             </ul>

                            @csrf


                            <script
                                src="//js.paystack.co/v1/inline.js"
                                data-key="{{ $data->key }}"
                                data-email="{{ $data->email }}"
                                data-amount="{{$data->amount}}"
                                data-currency="{{$data->currency}}"
                                data-ref="{{ $data->ref }}"
                                data-custom-button="btn-confirm"
                            >
                            </script>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection


@push('script')

@endpush
@push('style')

<style>
    .mt--5{
        margin-top: 100px
    }
</style>

@endpush