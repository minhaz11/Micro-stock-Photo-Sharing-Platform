@extends($activeTemplate .'layouts.frontend')

@section('content')

@include($activeTemplate.'partials.breadcrumb')
  <div class="pt-60 pb-60">
    <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header text-center text-white base--bg font-weight-bold">@lang('Please Verify Your Mobile to Get Access')</div>

                        <div class="card-body">

                            <form action="{{route('user.verify_sms')}}" method="POST" class="login-form">
                                @csrf

                                <div class="form-group">
                                    <p class="text-center">@lang('Your Mobile Number:')  <strong>{{auth()->user()->mobile}}</strong></p>
                                </div>


                                <div class="form-group">
                                    <h5 class="col-md-12 mb-4 text-center">@lang('Enter Verification Code')</h5>
                                    <div id="phoneInput">

                                        <div class="field-wrapper">
                                            <div class=" phone">
                                                <input type="text" name="sms_verified_code[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                                <input type="text" name="sms_verified_code[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                                <input type="text" name="sms_verified_code[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                                <input type="text" name="sms_verified_code[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                                <input type="text" name="sms_verified_code[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                                <input type="text" name="sms_verified_code[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="btn-area text-center">
                                        <button type="submit" class="cmn-btn">@lang('Submit')</button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <p class="text-center">@lang('Don\'t get code in your phone yet?') <a href="{{route('user.send_verify_code')}}?type=phone" class="forget-pass"> @lang('Resend code')</a></p>
                                    @if ($errors->has('resend'))
                                        <br/>
                                        <small class="text-danger">{{ $errors->first('resend') }}</small>
                                    @endif
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-lib')
    <script src="{{ asset($activeTemplateTrue.'js/jquery.inputLettering.js') }}"></script>
@endpush
@push('style')
    <style>

        #phoneInput .field-wrapper {
            position: relative;
            text-align: center;
        }

        #phoneInput .form-group {
            min-width: 300px;
            width: 50%;
            margin: 4em auto;
            display: flex;
            border: 1px solid rgba(96, 100, 104, 0.3);
        }

        #phoneInput .letter {
            height: 50px;
            border-radius: 0;
            text-align: center;
            max-width: calc((100% / 10) - 1px);
            flex-grow: 1;
            flex-shrink: 1;
            flex-basis: calc(100% / 10);
            outline-style: none;
            padding: 5px 0;
            font-size: 18px;
            font-weight: bold;
            color: red;
            border: 1px solid #0e0d35;
        }

        #phoneInput .letter + .letter {
        }

        @media (max-width: 480px) {
            #phoneInput .field-wrapper {
                width: 100%;
            }

            #phoneInput .letter {
                font-size: 16px;
                padding: 2px 0;
                height: 35px;
            }
        }

    </style>
@endpush

@push('script')
    <script>
        'use strict'
        $(function () {
            $('#phoneInput').letteringInput({
                inputClass: 'letter',
                onLetterKeyup: function ($item, event) {
                    console.log('$item:', $item);
                    console.log('event:', event);
                },
                onSet: function ($el, event, value) {
                    console.log('element:', $el);
                    console.log('event:', event);
                    console.log('value:', value);
                }
            });
        });
    </script>
@endpush
