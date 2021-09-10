@extends('reviewer.layouts.app')
@php
    $reviewer = auth()->guard('reviewer')->user();

@endphp
@section('panel')

    <div class="row mb-none-30">
        <div class="col-lg-3 col-md-3 mb-30">

            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-3 bg--primary align-items-center">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage('assets/reviewer/images/profile/'. $reviewer->image)}}" alt="profile-image">
                        </div>
                        <div class="pl-3">
                            <h4 class="text--white">{{$reviewer->firstname}}</h4>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('First Name')
                            <span class="font-weight-bold">{{$reviewer->firstname}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Last Name')
                            <span class="font-weight-bold">{{$reviewer->lastname}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span  class="font-weight-bold">{{$reviewer->username}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span  class="font-weight-bold">{{$reviewer->email}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Country')
                            <span  class="font-weight-bold">{{$reviewer->address->country}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('City')
                            <span  class="font-weight-bold">{{$reviewer->address->city}}</span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-9 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Profile Information')</h5>

                    <form action="{{ route('reviewer.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview" style="background-image: url({{ getImage('assets/reviewer/images/profile/'. auth()->guard('reviewer')->user()->image) }})">
                                                    <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg" required>
                                                <label for="profilePicUpload1" class="bg--success">@lang('Upload Image')</label>
                                                <small class="mt-2 text-facebook">@lang('Supported files:') <b>@lang('jpeg, jpg.')</b> @lang('Image will be resized into 400x400px') </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-8">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('First Name')</label>
                                    <input class="form-control" type="text" name="fname" value="{{ auth()->guard('reviewer')->user()->firstname }}" required>
                                </div>
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Last Name')</label>
                                    <input class="form-control" type="text" name="lname" value="{{ auth()->guard('reviewer')->user()->lastname }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Email')</label>
                                    <input class="form-control" type="email" name="email" value="{{ auth()->guard('reviewer')->user()->email }}" disabled>
                                </div>
                               
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('City')</label>
                                    <input class="form-control" type="text" name="city" value="{{ auth()->guard('reviewer')->user()->address->city }}" required>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('reviewer.password')}}" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="fa fa-key"></i>@lang('Password Setting')</a>
@endpush
