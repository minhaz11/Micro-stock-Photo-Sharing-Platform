@extends($activeTemplate.'layouts.user.master')

@section('content')
<div class="row mt-10">
    <div class="col-lg-12">
        <div class="card mb-30 p-0">
        <div class="card-header">
            <div class="mr-auto p-2 mt-3"><h6>{{ trans($page_title) }}</h6></div>
        </div>
        <div class="card-body p-0">
            <section class="pt-60 pb-60">
                <div class="container">
                  <div class="row justify-content-center photo-gallery">
                    @forelse ($photos as $item)
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                      <div class="photo-card has-link">
                      <a href="{{route('image.details',[$item->id,slug($item->title)])}}" target="_blank" class="item-link"></a>
                      <img src="{{imageUrl($item->image_thumb,'150x150')}}" alt="image" class="photo-card__thumb">
                        <div class="photo-card__content">
                          <div class="author">
                            
                            <a href="" class="name">{{__($item->title)}}</a>
                          </div>
                          <ul class="action-area">
                            <li>
                              <i class="lar la-heart"></i> 
                              <span class="total-count">@lang(number_format_short($item->likes->count()))</span>
                            </li>
                            <li>
                              <i class="las la-download"></i>
                              <span class="total-count">@lang(number_format_short($item->downloads->count()))</span>
                            </li>
                          </ul>
                        </div>
                      </div><!-- photo-card css start -->
                    </div>
                        
                    @empty
                    <div class="row">
                      <div class="col-lg-12 text-center">
                      <p>{{$empty_message}}</p>
                      </div>
                  </div>
                    @endforelse
                  </div>
                  @if (!$photos->isEmpty())
                  <div class="row justify-content-center">
                  <div class="col-lg-12 text-center">
                      {{$photos->links()}}
                  </div>
                </div>
                  @endif
                </div>
              </section>
        </div>
      </div><!-- card end -->
    </div>
  </div>
    
        
@stop

