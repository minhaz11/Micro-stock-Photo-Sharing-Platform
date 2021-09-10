@extends($activeTemplate.'layouts.frontend')

@section('content')

@php
$breadcrumb = getContent('breadcrumb.content',true)
@endphp
<!-- inner hero start -->
<section class="inner-hero bg_img" data-background="{{getImage('assets/images/frontend/breadcrumb/'.$breadcrumb->data_values->background_image,'1920x1080')}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <h2 class="inner-hero__title  text-center mb-4">@lang('The best Premium stock photos shared by talented
                    creators.')</h2>
                <div class="hero__search">
                    <select class="niceSelect" onChange='window.location.href=this.value'>
                        @foreach($categories as $item)
                            <option
                                value="{{ route('image.category',[$item->id, slug($item->name)]) }}"
                                {{ ($item->id == $category->id) ? 'selected' : '' }}>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                    <form class="hero__search-form" action="{{ route('search.photos') }}"
                        method="GET">
                        @csrf
                        <label class="search-form-label">
                            <span class="label-txt">@lang('Search here'): <span class="typing"></span></span>
                            <input type="text" name="search" id="hero-search-field"
                                class="form-control hero-search-field">
                        </label>
                        <button type="submit" class="search-btn"><i class="las la-search"></i></button>
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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-header text-center">
                    <h2 class="section-title">@lang($category->name)</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center photo-gallery">
            @forelse($images as $image)
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="photo-card has-link">
                        @if ($image->premium==1)
                        <span class="photo-card__badge"><i title="Premium" data-toggle="tooltip" class="las la-crown"></i></span>
                            
                        @endif
                        <a href="{{ route('image.details',[$image->id,slug($image->title)]) }}"
                            class="item-link"></a>
                        <img src="{{imageUrl($image->image_thumb)}}"
                            alt="image" class="photo-card__thumb">
                        <div class="photo-card__content">
                            <div class="author">
                                <img src="{{ getImage('assets/user/profile/'.$image->user->image) }}"
                                    alt="image">
                                <a href="javascript:void(0)" class="name">{{ $image->user->username }}</a>
                            </div>
                            <ul class="action-area">
                                <li>
                                    <i class="lar la-heart"></i>
                                    <span class="total-count">@lang(number_format_short($image->likes->count()))</span>
                                </li>
                                <li>
                                    <i class="las la-download"></i>
                                    <span
                                        class="total-count">@lang(number_format_short($image->downloads->count()))</span>
                                </li>
                            </ul>
                        </div>
                    </div><!-- photo-card css start -->
                </div>
                @empty
            <p class="my-5">{{'No Photos in this category'}}</p>
                @endforelse
        </div>
    </div>

</section>
@endsection


