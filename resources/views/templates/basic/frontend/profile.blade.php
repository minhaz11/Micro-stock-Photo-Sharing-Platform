@extends($activeTemplate.'layouts.frontend')

@php
$breadcrumb = getContent('breadcrumb.content',true)
@endphp

@section('content')
<section class="author-section">
<div class="image-holder bg_img" data-background="{{getImage('assets/images/frontend/breadcrumb/'.$breadcrumb->data_values->background_image,'1920x1080')}}"></div>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="author">
          <div class="author-widget__thumb thumb position-relative">
            <img src="{{getImage('assets/user/profile/'.$profile->image)}}" alt="image">
            @if ($profile->myBadge)
            <span class="author-badge style--two">
              <img src="{{getImage('assets/images/badge/'.$profile->myBadge->badge->icon)}}" data-toggle="tooltip" data-placement="bottom" title="{{$profile->myBadge->badge->name.' author'}}">
            </span>
            @endif
          </div>
            <div class="author__content">
              <ul class="stat-list">
                <li>
                  <p class="stat-amount">{{trans(number_format_short($profile->images->count()))}}</p>
                  <span class="caption">@lang('Photos')</span>
                </li>
                <li>
                <p class="stat-amount">{{trans(number_format_short($profile->downloadCount()))}}</p>
                  <span class="caption">@lang('Downloads')</span>
                </li>
                <li>
                  <p class="stat-amount">{{trans(number_format_short($profile->likeCount()))}}</p>
                  <span class="caption">@lang('Likes')</span>
                </li>
                <li>
                  <p class="stat-amount" id="count">{{trans(number_format_short($profile->follower($profile->id)))}}</p>
                  <a href="javascript:void(0)" class="caption">@lang('Followers')</a>
                </li>
                <li>
                  <p class="stat-amount">{{trans(number_format_short($profile->follows->count()))}}</p>
                  <a href="javascript:void(0)" class="caption">@lang('Following')</a>
                </li>
                
              </ul>
              <div class="content-area">
                <div class="left">
                <h4 class="name mt-3"><span>{{$profile->firstname.' '.$profile->lastname}}</span></h4>
                  <ul class="author__meta">
                  <li><i class="las la-user"></i> @lang('Memeber since') {{trans(showDateTime($profile->created_at,'d M, Y'))}}</li>
                    <li><i class="las la-map-marker"></i>@lang($profile->country)</li>
                  </ul>
                </div>
                <div class="right" id="right">
                  @if (auth()->guest())
                   <a href="{{route('user.login')}}" class="cmn-btn btn-sm ml-md-4 mt-md-0 mt-3 follow"><i class="las la-plus"></i> @lang('Follow')</a>
                  @else

                       @if (auth()->id()!=$profile->id)
                          @if ($user::follow($user->id,$profile->id))
                          <a href="javascript:void(0)" title="{{trans('click to unfollow')}}" class="border-btn btn-sm ml-md-4 mt-md-0 mt-3 follow" data-follower="{{$user->id}}" data-followed="{{$profile->id}}"><i class="las la-check-double"></i>  @lang('Following')</a>
                          @else
                          <a href="javascript:void(0)" class="cmn-btn btn-sm ml-md-4 mt-md-0 mt-3 follow" data-follower="{{$user->id}}" data-followed="{{$profile->id}}"><i class="las la-plus"></i> @lang('Follow')</a>
                          @endif
                       @endif
                      
                  @endif
                    
                </div>
              </div><!-- content-area end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="pt-120 pb-120">
    <div class="container-fluid">
      <div class="row justify-content-center photo-gallery">
          @forelse ($images as $image)
          <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="photo-card has-link">
              @if ($image->premium==1)
              <span class="photo-card__badge"><i title="Premium" data-toggle="tooltip" class="las la-crown"></i></span>
                  
              @endif
            <a href="{{route('image.details',[$image->id,slug($image->title)])}}" class="item-link"></a>
            <img src="{{imageUrl($image->image_thumb)}}" alt="image" class="photo-card__thumb">
              <div class="photo-card__content">
                <div class="author">
                  <img src="{{getImage('assets/user/profile/'.$profile->image)}}" alt="image">
                  <a href="#0" class="name">{{$profile->username}}</a>
                </div>
                <ul class="action-area">
                  <li>
                    <i class="lar la-heart"></i> 
                    <span class="total-count">{{number_format_short($image->likes->count())}}</span>
                  </li>
                  <li>
                    <i class="las la-download"></i>
                    <span class="total-count">{{number_format_short($image->downloads->count())}}</span>
                  </li>
                </ul>
              </div>
            </div><!-- photo-card css start -->
          </div>
          @empty
              <p>@lang('No Photos!')</p>
          @endforelse
      </div>
      <div class="row">
        <div class="col-lg-12 text-center">
         {{$images->links()}}
        </div>
      </div>
    </div>
  </div>
@endsection

@push('script')

<script>
  'use strict'
   //follow 
   $(document).on('click','.follow',function(){
    var url = '{{route("follow")}}'
    var data = {
      follower : $(this).data('follower'),
      followed: $(this).data('followed')
    }
    axios.post(url,data, {
    headers: {
      'Content-Type': 'application/json',
    }
 
  })
  .then(function (response) {
    $("#right").load(location.href + " #right");
    $("#count").load(location.href + " #count");
    iziToast.success({message:response.data.success, position: "topRight"})

  })
  .catch(function (error) {
    console.log(error);
  })

  })

</script>

@endpush