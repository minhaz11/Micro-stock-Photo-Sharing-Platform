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
                                <script src="{{$data->checkout_js}}"
                                    @foreach($data->val as $key=>$value)
                                    data-{{$key}}="{{$value}}"
                                     @endforeach >

                            </script>
                            </li>
                         </ul>

                         <input type="hidden" custom="{{$data->custom}}" name="hidden">

                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection


@push('script')
    <script>
        $(document).ready(function () {
            $('input[type="submit"]').addClass(" ml-4 cmn-btn mt-4 btn-custom2 text-center btn-lg");
        })
    </script>
@endpush
