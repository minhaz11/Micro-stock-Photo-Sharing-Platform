@extends($activeTemplate.'layouts.user.master')

@section('content')
@php
    $pricing = getContent('pricing.content',true);
    $user = auth()->user();
@endphp

      <div class="row justify-content-center mb-none-30">


        @forelse ($subscriptions as $key => $item)

        <div class="col-xl-4 col-md-6 mb-30">
          <div class="pricing-card">
            <div class="pricing-card__icon">
              <i class="{{$item->icon}} color--6"></i>
            </div>
            <div class="pricing-card__content text-center">
              <h4 class="package-name">@lang($item->name)</h4>
              <ul class="pricing-card__list">
                @foreach ($item->facilities as $facility)
                <li class="active">{{$facility}}</li>

                @endforeach

              </ul>
              <h5 class="package-amount color--1">{{$general->cur_sym}} {{getAmount($item->price,2)}} <small>/@lang('Yearly')</small></h5>
             @if ($user->plan())
                <button class="btn mt-4 get d-block w-100 text-center" disabled
                >{{$user->plan($item->id)? trans('PURCHASED'):trans($pricing->data_values->button_text)}}</button>
             @else
                <a href="#0" class="cmn-btn mt-4 get d-block w-100 text-center purchase" data-toggle="modal" data-target="#purchaseModal"
                data-planroute = "{{route('user.purchase.subscription',$item->id)}}" data-route="{{route('user.payment',$item->id)}}"
                >@lang($pricing->data_values->button_text)</a>
             @endif
            </div>
          </div><!-- pricing-card end   -->
        </div>




        @empty
        <div class="col-xl-12 col-md-6 mb-30">
         <div class="card">
           <div class="card-body text-center">
             <h5>@lang('No Pricing Plan Available!!')</h5>
           </div>
         </div>
        </div>
        @endforelse

      </div><!-- row end -->


    <div class="modal fade" id="purchaseModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
         <button type="button" class="close ml-auto m-3" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          <div class="modal-body text-center">

              <i class="fas fa-hand-holding-usd icon text-info mb-15"></i>
              <h4 class="text--secondary mb-15">@lang('Please choose your payment option!')</h4>

              </div>
          <div class="modal-footer justify-content-center">
            <a href=""  class="btn btn-warning planpurchase">@lang('From Balance')</a>
            <a href=""  class="btn btn-primary gateway">@lang('From Gateway')</a>
          </div>

        </div>
      </div>
  </div>

  @stop

  @push('script')

  <script>
       'user script';
        $('.purchase').on('click',function(){
        var route = $(this).data('route')
        var planroute = $(this).data('planroute')

        var modal = $('#purchaseModal');
        $('.gateway').attr('href',route)
        $('.planpurchase').attr('href',planroute)
        modal.modal('show');


    })

  </script>

  @endpush

  @push('style')

  <style>
      button:disabled,
        button[disabled]{
        padding: 12px 35px;
        background-color: #cccccc;
        color: #666666;
        }

        .icon{
          font-size: 70px
        }
  </style>

  @endpush
