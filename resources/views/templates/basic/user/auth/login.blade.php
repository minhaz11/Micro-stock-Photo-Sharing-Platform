@extends($activeTemplate.'layouts.frontend')

@php
    $login = getContent('login.content',true)->data_values;
@endphp

@section('content')


     <!-- inner hero start -->
     @include($activeTemplate.'partials.breadcrumb')
      <!-- inner hero end -->
    <!-- acount section start -->
    <section class="pt-120 pb-120">
      <div class="container">
        <div class="row justify-content-center acount-wrapper">
          <div class="col-lg-6 bg_img" data-background="{{getImage('assets/images/frontend/login/'.$login->background_image,'1920x1080')}}"></div>
          <div class="col-lg-6">
            <div class="acount-area">
              <div class="text-center">
                <h2 class="title mb-3">@lang('Login to '.$general->sitename )</h2>
                <p class="mt-3">@lang('Please enter your valid credentials to login')</p>
              </div>
    
              <form class="action-form mt-50 user" method="POST" action="{{ route('user.logging')}}" onsubmit="return submitUserForm();">
                @csrf
                <div class="form-group">
                  <label>@lang('Username')</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text"><i class="las la-user"></i></div>
                    </div>
                <input type="text" class="form-control" name="username" value="{{old('username')}}" placeholder="{{trans('Email or username')}}" required>
                  </div>
                </div><!-- form-group end -->
                <div class="form-group">
                  <label>@lang('Password')</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text"><i class="las la-key"></i></div>
                    </div>
                    <input type="password" name="password"  class="form-control" placeholder="{{trans('Password')}}" required>
                  </div>
                </div><!-- form-group end -->
                <div class="form-group row">
                    <div class="col-md-4 ">
                    </div>
                    <div class="col-md-6 ">
                        @php echo recaptcha() @endphp
                    </div>
                </div>
                @include($activeTemplate.'partials.custom-captcha')
                <div class="text-center mt-4">
                  <button type="submit" class="cmn-btn rounded-0 w-100">@lang('Login Now')</button>
                  <p class="mt-20 link">@lang('Haven\'t an account?') <a href="{{route('user.register')}}">@lang('Create an account')</a></p>
                  <p class="mt-20 link"><a href="{{route('user.password.request')}}">@lang('Forgot Password?')</a></p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- acount section end -->
@endsection

@push('script')
    <script>
      'use strict'
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
        function verifyCaptcha() {
            document.getElementById('g-recaptcha-error').innerHTML = '';
        }


    </script>
@endpush

@push('style')
    
<style>
   
   
</style>

@endpush
