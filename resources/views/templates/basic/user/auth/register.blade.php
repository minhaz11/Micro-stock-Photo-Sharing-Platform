@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
    $login = getContent('login.content',true)->data_values;
@endphp
@include($activeTemplate.'partials.breadcrumb')


  <!-- acount section start -->
  <section class="pt-120 pb-120">
    <div class="container">
      <div class="row justify-content-center acount-wrapper">
      <div class="col-lg-6 bg_img" data-background="{{getImage('assets/images/frontend/login/'.$login->background_image,'1920x1080')}}"></div>
        <div class="col-lg-6">
          <div class="acount-area">
            <div class="text-center">
              <h2 class="title mb-3">@lang('Sign Up to '.$general->sitename )</h2>
            <p>@lang('Please Enter your valid informations to sign up')</p>
            </div>
            <form class="action-form mt-50" action="{{ route('user.register') }}" method="POST"
            onsubmit="return submitUserForm();">
            @csrf
            @if(session()->get('reference') != null)
            <div class="form-group">
                <label>{{ __('Reference BY') }}</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="las la-user"></i></div>
                      </div>
                      <input type="text" name="referBy" id="referenceBy" class="form-control"
                           value="{{session()->get('reference')}}" readonly>

                </div>
            </div>
            @endif

              <div class="row">
                <div class="form-group col-md-6">
                  <label>@lang('First name')</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text"><i class="las la-user"></i></div>
                    </div>
                    <input id="firstname" type="text" class="form-control" name="firstname"
                    value="{{ old('firstname') }}" placeholder="@lang('First Name')"  required>
                  </div>
                </div><!-- form-group end -->
                <div class="form-group col-md-6">
                  <label>@lang('Last name')</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text"><i class="las la-user"></i></div>
                    </div>
                    <input id="lastname" type="text" class="form-control" name="lastname"
                    value="{{ old('lastname') }}" placeholder="@lang('Last Name')" required>
                  </div>
                </div><!-- form-group end -->
              </div>
              <div class="form-group">
                <label>@lang('Email address')</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="las la-at"></i></div>
                  </div>
                  <input id="email" type="email" class="form-control" name="email"
                                           value="{{ old('email') }}" placeholder="@lang('Email')" required>
                </div>
              </div><!-- form-group end -->
              <div class="form-group">
               <div class="row">
                   <div class="col-md-12">
                   <div class="country-code">
                    <label for="mobile" class="">@lang('Phone')</label>
                        <div class="input-group has-select">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <select name="country_code">
                                        @include('partials.country_code')
                                    </select>
                                </span>
                            </div>
                            <input type="text" name="mobile" class="form-control" placeholder="@lang('Your Phone Number')" required>
                        </div>
                      </div>
                    </div>

               </div>
              </div><!-- form-group end -->

              <div class="form-group">
                <label>@lang('Country')</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="las la-globe"></i></div>
                  </div>
                  <input  type="text" class="form-control" name="country" readonly  value="{{ old('country') }}" placeholder="@lang('Country')" required>
                </div>
              </div><!-- form-group end -->

             <div class="row">
              <div class="col-md-12">
                <label>@lang('Username')</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="las la-user"></i></div>
                  </div>
                  <input id="username" type="text" class="form-control" name="username"
                  value="{{ old('username') }}" placeholder="@lang('Username')" required>
                </div>
               </div>
              <div class="form-group col-md-6">
                <label>@lang('Password')</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="las la-key"></i></div>
                  </div>
                  <input id="password" type="password" class="form-control" name="password" placeholder="Password" required
                  autocomplete="new-password">
                </div>
              </div><!-- form-group end -->
              <div class="form-group col-md-6">
                <label>@lang('Confirm Password')</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="las la-key"></i></div>
                  </div>
                  <input id="password-confirm" type="password" class="form-control"
                  name="password_confirmation" placeholder="@lang('Confirm Password')" required autocomplete="new-password">
                </div>
              </div>
             </div>
             @include($activeTemplate.'partials.custom-captcha')<!-- form-group end -->
              <div class="form-group">
                    @php echo recaptcha() @endphp
             </div>
             
              <div class="mt-4 text-center">
                <button type="submit" class="cmn-btn rounded-0 w-100">@lang('Signup Now')</button>
              <p class="mt-20 link">@lang('Already i have an account? ') <a href="{{route('user.login')}}">@lang('Sign In')</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
@push('style')

  <style>
    .input-group.has-select .input-group-text {
      padding: 0;
      border: 0;
    }
    @media (max-width: 480px) {
      .input-group.has-select .input-group-prepend,
      .input-group.has-select .input-group-prepend .input-group-text {
        display: block;
        width: 100%;
      }
      .input-group.has-select .input-group-prepend .input-group-text,
      .input-group.has-select .input-group-prepend select {
        border-radius: 5px 5px 0 0;
        border-bottom: none;
      }
      .input-group.has-select .form-control  {
        border-radius: 0 0 5px 5px;
      }

    }
  </style>

@endpush




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


        @if($country_code)
        var t = $(`option[data-code={{ $country_code }}]`).attr('selected','');
        @endif
        $('select[name=country_code]').change(function(){
            $('input[name=country]').val($('select[name=country_code] :selected').data('country'));
        }).change();
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
