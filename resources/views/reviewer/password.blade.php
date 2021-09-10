@extends('reviewer.layouts.app')
@php
    $reviewer = auth()->guard('reviewer')->user();
@endphp
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-3 col-md-3 mb-30">

            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-3 bg--primary">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage('assets/reviewer/images/profile/'. $reviewer->image)}}" alt="profile-image">
                        </div>
                        <div class="pl-3">
                            <h4 class="text--white">{{$reviewer->firstname}}</h4>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Name')
                            <span class="font-weight-bold">{{ $reviewer->firstname }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span  class="font-weight-bold">{{ $reviewer->username }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span  class="font-weight-bold">{{ $reviewer->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Country')
                            <span  class="font-weight-bold">{{ $reviewer->address->country }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('City')
                            <span  class="font-weight-bold">{{ $reviewer->address->city }}</span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-9 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Change Password')</h5>

                    <form action="{{ route('reviewer.password.update') }}" method="POST">
                        @csrf

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">@lang('Password')</label>
                            <div class="col-lg-9">

                                <input class="form-control" type="password" name="old_password" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">@lang('New password')</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="password" name="password" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">@lang('Confirm password')</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="password" name="password_confirmation" required>
                            </div>
                        </div>


                        <div class="form-group row">

                            <label class="col-lg-3 col-form-label form-control-label"></label>
                            <div class="col-lg-9">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('reviewer.profile')}}" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="fa fa-user"></i>@lang('Profile Setting')</a>
@endpush
