@extends($activeTemplate.'layouts.frontend')

@php
    $content = getContent('contact.content',true)->data_values;
@endphp

@section('content')
@include($activeTemplate.'partials.breadcrumb')

  <section class="pt-120 pb-120">
    <div class="container">
      
      <div class="row justify-content-center pb-60 text-center">
       
        <div class="col-sm-6 col-xl-4 mb-30">
          <div class="choose-card">
            <div class="choose-card__icon base--color">
              <i class="las la-address-book"></i>
            </div>
            <div class="choose-card__content">
              <h4 class="title mb-3">@lang('Address')</h4>
              <p>{{__(@$content->address)}}</p>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xl-4 mb-30">
          <div class="choose-card">
            <div class="choose-card__icon base--color">
              <i class="las la-envelope"></i>
            </div>
            <div class="choose-card__content">
              <h4 class="title mb-3">@lang('Mail Address')</h4>
              <p>{{__(@$content->email_address)}}</p>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xl-4 mb-30">
          <div class="choose-card">
            <div class="choose-card__icon base--color">
              <i class="las la-phone-volume"></i>
            </div>
            <div class="choose-card__content">
              <h4 class="title mb-3">@lang('Phone No.')</h4>
              <p>{{__(@$content->phone_number)}}</p>
            </div>
          </div>
        </div>
      
    </div>
      <div class="contact-wrapper">
      <form action="{{route('contact.send')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-6 contact-thumb bg_img" data-background="{{getImage('assets/images/frontend/contact/'.@$content->background_image,'1920x1080')}}"></div>
              <div class="col-lg-6 contact-form-wrapper">
                <h2 class="font-weight-bold">@lang('Get in touch')</h2>
                <span>@lang('Leave us a message')</span>
                <form class="contact-form mt-4">
                  <div class="form-row">
                    <div class="form-group col-lg-6">
                    <input type="text" name="name" placeholder="{{trans('Full Name')}}" class="form-control">
                    </div>
                    <div class="form-group col-lg-6">
                    <input type="email" name="email" placeholder="{{trans('Email Address')}}" class="form-control">
                    </div>
                    <div class="form-group col-lg-12">
                    <input class="form-control" placeholder="{{trans('Subject')}}" name="subject">
                    </div>
                    <div class="form-group col-lg-12">
                      <textarea class="form-control" placeholder="@lang('Message')" name="message"></textarea>
                    </div>
                    <div class="col-lg-12">
                      <button type="submit" class="cmn-btn">@lang('Send Message')</button>
                    </div>
                  </div>
                </form>
              </div>
            </div> 
         </form>
      </div>
    </div>
  </section>
  @endsection
