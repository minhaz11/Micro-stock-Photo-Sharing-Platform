@extends($activeTemplate.'layouts.frontend')
@section('content')
@include($activeTemplate.'partials.breadcrumb')
<section class="pt-100 pb-100">
    <div class="container">
      <div class="row justify-content-center ">
        <div class="col-lg-8 mt-5">
          <div class="section-header text-center">
            <h2 class="section-title">@lang(@$faq->data_values->heading)</h2>
            <p>{{__(@$faq->data_values->short_details)}}</p>
          </div>
        </div>
      </div>
      <div class="row gy-4 justify-content-center pb-50">
        <div class="col-xl-12">
          <div class="contact-form-wrapper border-0">
            <div class="faq-wrapper style-two">
                @foreach ($faqs as $faq)
                <div class="faq-item">
                    <div class="faq-title">
                        <h6 class="title">{{__($faq->data_values->question)}}</h6>
                        <div class="right-icon"></div>
                    </div>
                    <div class="faq-content">
                        <p>
                            @php
                                echo $faq->data_values->answer;
                            @endphp
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@stop
@push('style')

<style>
    .faq-content{
        display: none;
    }
</style>

@endpush