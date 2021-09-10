@extends($activeTemplate.'layouts.frontend')

@php
$breadcrumb = getContent('breadcrumb.content',true)
@endphp

@section('content')

 <section class="inner-hero bg_img" data-background="{{getImage('assets/images/frontend/breadcrumb/'.$breadcrumb->data_values->background_image,'1920x1080')}}">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
              <h2 class="inner-hero__title  text-center mb-4">@lang('The best Premium stock photos shared by talented creators.')</h2>
              <div class="hero__search">
                <select class="niceSelect" onChange = 'window.location.href=this.value'>
                  <option selected value="{{url()->current()}}">@lang('Select Category')</option>
                  @foreach ($categories as $item)
                  <option value="{{route('image.category',[$item->id, slug($item->name)])}}">{{$item->name}}</option>
                  @endforeach
                  </select> 
                <form class="hero__search-form" action="{{route('search.photos')}}" method="GET">
                    @csrf
                        <label class="search-form-label">
                        <input type="text" name="search" id="hero-search-field" placeholder="{{trans('Search here')}}" class="form-control hero-search-field" value="{{$value??old('search')}}">
                        </label>
                        <button type="submit" class="search-btn"></button>
                    </form>
                </div>
            </div>
          </div>
        </div>
    </section>
      <!-- inner hero end -->
  
      <!-- photo section start -->
      <section class="pt-60 pb-120">
        <div class="container">
          <div class="filter-menu-wrapper">
            <div class="row justify-content-center align-items-center">
              <div class="col-lg-10 col-md-9">
                
                <ul class="d-flex flex-wrap aling-items-center filter-menu main-menu">
                    <li class="{{request()->input('filter')==''?'active':''}}"><a href="{{route('view.all')}}">@lang('All')</a></li>
                    <li class="{{request()->input('filter')=='latest'?'active':''}}"><a href="{{queryBuild('filter','latest') }}">@lang('Latest')</a></li>
                    <li class="{{request()->input('filter')=='premium'?'active':''}}"><a href="{{queryBuild('filter','premium')}}">@lang('Premium')</a></li>
                    <li class="{{request()->input('filter')=='top-premium'?'active':''}}"><a href="{{queryBuild('filter','top-premium')}}">@lang('Top Premium')</a></li>
                    <li class="{{request()->input('filter')=='free'?'active':''}}"><a href="{{queryBuild('filter','free')}}">@lang('Free')</a></li>
                    <li class="{{request()->input('filter')=='top-free'?'active':''}}"><a href="{{queryBuild('filter','top-free')}}">@lang('Top Free')</a></li>
                  
                    <li class="{{request()->input('filter')=='most-views'?'active':''}}"><a href="{{queryBuild('filter','most-views')}}">@lang('Most Views')</a></li>
                    <li class="{{request()->input('filter')=='most-downloads'?'active':''}}"><a href="{{queryBuild('filter','most-downloads')}}">@lang('Most Downloads')</a></li>
                  </ul>
              </div>
              <div class="col-lg-2 col-md-3 mt-md-0 mt-3">
                <select class="niceSelect filter-select" onChange="window.location.href=this.value">
                    <option value="{{url()->current().'?filter='.request()->input('filter')}}">@lang('All time')</option>
                    <option value="{{queryBuild('timeframe','1')}}" {{request()->input('timeframe')=='1'?'selected':''}}>@lang('Today')</option>
                    <option value="{{queryBuild('timeframe','7')}}" {{request()->input('timeframe')=='7'?'selected':''}}>@lang('This week')</option>
                    <option value="{{queryBuild('timeframe','30')}}" {{request()->input('timeframe')=='30'?'selected':''}}>@lang('This month')</option>
                    <option value="{{queryBuild('timeframe','365')}}" {{request()->input('timeframe')=='365'?'selected':''}}>@lang('This year')</option>
                  </select>
              </div>
            </div>
          </div>
        </div>
        @if(!$images->isEmpty())
        <div class="container-fluid">
          <div class="row justify-content-center photo-gallery">
              @foreach ($images as $image)
              <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="photo-card has-link">
                  @if ($image->premium==1)
                  <span class="photo-card__badge"><i title="Premium" data-toggle="tooltip" class="las la-crown"></i></span>                 
                  @endif
                
                  <a href="{{route('image.details',[$image->image_id,slug($image->image->title)])}}" class="item-link"></a>
                   <img src="{{imageUrl($image->image->image_thumb,'320x250')}}" alt="image" class="photo-card__thumb">
                  <div class="photo-card__content">
                    <div class="author">
                      <img src="{{getImage('assets/user/profile/'.$image->image->user->image)}}" alt="image">
                      <a href="javascript:void(0)" class="name">{{$image->image->user->username}}</a>
                    </div>
                    <ul class="action-area">
                        <li>
                          <i class="lar la-heart"></i> 
                        <span class="total-count">@lang(number_format_short($image->image->likes->count()))</span>
                        </li>
                        <li>
                          <i class="las la-download"></i>
                          <span class="total-count">@lang(number_format_short($image->image->downloads->count()))</span>
                        </li>
                      </ul>
                  </div>
                </div><!-- photo-card css start -->
              </div>
              @endforeach
        </div>
    </div> 
    @else 
    <div class="container-fluid mb-5 mt-5">
      <div class="row justify-content-center photo-gallery">
          <h6>@lang('No Photos Available')</h6>
        
      </div>
    </div>  
    
    @endif
{{$images->links()}}
</section>
@endsection
