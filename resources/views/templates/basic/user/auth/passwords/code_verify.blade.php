@extends($activeTemplate.'layouts.frontend')
@section('content')
@include($activeTemplate.'partials.breadcrumb')
  <div class="pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header base--bg " >
                    <label for="email" class="text-white">@lang('Verification Code')</label>
                </div>
                <div class="card-body">
                        <form action="{{ route('user.password.verify-code') }}" method="POST" class="cmn-form mt-30">
                            @csrf
        
                            <input type="hidden" name="email" value="{{ $email }}">
        
                            <div class="form-group">
                               
        
                                <div id="phoneInput">
                                    <div class="field-wrapper">
                                        <div class=" phone">
                                            <input type="text" name="code[]" class="letter"
                                                   pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                            <input type="text" name="code[]" class="letter"
                                                   pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                            <input type="text" name="code[]" class="letter"
                                                   pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                            <input type="text" name="code[]" class="letter"
                                                   pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                            <input type="text" name="code[]" class="letter"
                                                   pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                            <input type="text" name="code[]" class="letter"
                                                   pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                        </div>
                                    </div>
                                </div>
        
                            </div>
        
                            <div class="form-group text-center">
                                <button type="submit" class="cmn-btn ">@lang('Verify Code') <i
                                        class="las la-sign-in-alt"></i></button>
                            </div>
        
                            <div class="form-group text-center link">
                                @lang('Please check including your Junk/Spam Folder. if not found, you can ')
                                <a href="{{ route('user.password.request') }}">@lang('Try to send again')</a>
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
