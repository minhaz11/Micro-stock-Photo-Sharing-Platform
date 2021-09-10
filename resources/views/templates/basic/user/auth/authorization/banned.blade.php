@extends($activeTemplate .'layouts.frontend')
@section('content')
@include($activeTemplate.'partials.breadcrumb')
  <div class="pt-60 pb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{__($page_title)}}</div>

                    <div class="card-body">
                        <a href="{{route('user.logout')}}" class="btn btn-success"><i class="fa fa-sign-out"></i> @lang('Log Out')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

  </div>
@endsection