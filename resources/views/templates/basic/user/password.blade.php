@extends($activeTemplate.'layouts.user.master')

@section('content')
   
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">

                <div class="card">

                    <div class="card-body">

                    <form action="{{route('user.change.pass')}}" method="post" class="register">
                            @csrf
                            <div class="form-group">
                                <label for="password">{{trans('current password')}}</label>
                                <input id="password" type="password" placeholder="@lang('Current Password')" class="form-control" name="current_password" required
                                       autocomplete="current-password">
                            </div>

                            <div class="form-group">
                                <label for="password">{{trans('password')}}</label>
                                <input id="password" type="password" placeholder="@lang('New Password')" class="form-control" name="password" required
                                       autocomplete="current-password">
                            </div>


                            <div class="form-group">
                                <label for="confirm_password">{{trans('confirm password')}}</label>
                                <input id="password_confirmation" placeholder="@lang('Confirm Password')" type="password" class="form-control"
                                       name="password_confirmation" required autocomplete="current-password">
                            </div>


                            <div class="form-group">
                                <button class="cmn-btn">@lang('Change Password')</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>

@endsection


@push('script')

@endpush

