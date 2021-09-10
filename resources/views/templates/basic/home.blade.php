@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
	$hero = getContent('hero.content',true);
	$latest = getContent('latest.content',true)->data_values;

@endphp

<section class="hero bg_img" data-background="{{getImage('assets/images/frontend/hero/'.@$hero->data_values->background_image,'1920x1280')}}">
	<div class="container">
	  <div class="row justify-content-center">
		<div class="col-xl-6 col-lg-8">
			<h2 class="hero__title text-center mb-4">{{__($hero->data_values->heading)}}</h2>
			<div class="hero__search">
			   <select class="niceSelect" onChange = 'window.location.href=this.value'>
				<option selected value="{{url()->current()}}">@lang('Select Category')</option>
				@foreach ($categories as $item)
				<option value="{{route('image.category',[$item->id, slug($item->name)])}}">{{$item->name}}</option>
				@endforeach
				</select>
			    <form class="hero__search-form" action="{{route('search.photos')}}" method="GET">
					<label class="search-form-label">
					<span class="label-txt">@lang('Search here'): <span class="typing"></span></span>
					<input type="text" name="search" id="hero-search-field" class="form-control hero-search-field">
					</label>
					<button type="submit" class="search-btn"><i class="las la-search"></i></button>
				</form>
			</div>
		</div>
	  </div>
	</div>
  </section>
  <!-- hero end -->

  <!-- category section start -->
  <section class="category-section">
	<div class="container">
	  <div class="category-slider">
       @forelse ($categories as $category)

		<div class="single-slide">
			<div class="category-card has-link">
				<a href="{{route('image.category',[$category->id, slug($category->name)])}}" class="item-link"></a>
				<div class="category-card__thumb">
				<img src="{{getImage('assets/images/category/'.$category->image,'200x250')}}" alt="image">
				</div>
				<div class="category-card__content">
				<h6 class="title">@lang($category->name)</h6>

				<span class="total-amount">{{number_format_short(count($category->images))}}</span>
				</div>
			</div><!-- category-card end -->
		</div><!-- single-slide end -->
		@empty
			@for ($i = 0; $i <= 4; $i++)
			<div class="single-slide">
				<div class="category-card has-link">
					<a href="javascript:void(0)" class="item-link"></a>
					<div class="category-card__thumb">
					<img src="{{getImage('assets/images/category','205x250')}}" alt="image">
					</div>
					<div class="category-card__content">
					<h6 class="title">@lang('Category')</h6>

					<span class="total-amount">0</span>
					</div>
				</div>
			</div>
			@endfor

        @endforelse
	  </div>
	</div>
  </section>
  <!-- category section end -->

  <!-- photo section start -->

<section class="pt-120 pb-120">
	<div class="container">
	  <div class="row justify-content-center">
		<div class="col-lg-8">
		  <div class="section-header text-center">
			<h2 class="section-title">{{__(@$latest->heading)}}</h2>
			<p>{{__(@$latest->short_details)}}</p>
		  </div>
		</div>
	  </div>
	</div>
	<div class="container">
	  <div class="row justify-content-center photo-gallery">
		@forelse ($images as $image)
		<div class="col-lg-3 col-md-4 col-sm-6">
		  <div class="photo-card has-link">
			  @if ($image->premium==1)
			  <span class="photo-card__badge"><i title="Premium" data-toggle="tooltip" class="las la-crown"></i></span>
			  @endif
		  <a href="{{route('image.details',[$image->id,slug($image->title)])}}" class="item-link"></a>
		  <img src="{{imageUrl($image->image_thumb,'320x250')}}" alt="image" class="photo-card__thumb" lazy="loading">
			<div class="photo-card__content">
			  <div class="author">
			  <img src="{{getImage('assets/user/profile/'.$image->user->image,'35x35')}}" alt="image">
			  <a href="javascrip:void(0)" class="name">{{$image->user->username}}</a>
			  </div>
			  <ul class="action-area">
				<li>
				  <i class="lar la-heart"></i>
				<span class="total-count">@lang(number_format_short($image->likes->count()))</span>
				</li>
				<li>
				  <i class="las la-download"></i>
				  <span class="total-count">@lang(number_format_short($image->downloads->count()))</span>
				</li>
			  </ul>
			</div>
		  </div><!-- photo-card css start -->
		</div>
		@empty
		<div class="col-12">
			<h6 class="text-center no-photo">@lang('No Photos Available')</h6>
		</div>
		@endforelse
	  </div>
	  @if (!$images->isEmpty())
	  <div class="row mt-50">
		<div class="col-lg-12 text-center">
		<a href="{{route('view.all')}}" class="cmn-btn">@lang('view more')</a>
		</div>
	  </div>
	  @endif

	</div>
 </section>

@if($sections->secs != null)
	@foreach(json_decode($sections->secs) as $sec)
		@include($activeTemplate.'sections.'.$sec)
	@endforeach
@endif

@endsection

@push('script')
<script>
	var typed = new Typed('.typing', {
	  strings: [
		'Title',
		'Tags'
	  ],
	  loop: true,
	  showCursor: false,
	  typeSpeed: 50,
	  backSpeed: 20
	});
  </script>
@endpush
