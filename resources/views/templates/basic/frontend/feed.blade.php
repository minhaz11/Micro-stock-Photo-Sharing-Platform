
@extends($activeTemplate.'layouts.frontend')

@section('content')
    
    <!-- inner hero start -->
    @include($activeTemplate.'partials.breadcrumb')
      <!-- inner hero end -->

      <!-- photo section start -->
      <section class="pt-60 pb-120">
      
        <div class="container-fluid">
          <div class="row justify-content-center photo-gallery">
              @foreach ($feedImages as $item)
                @forelse ($item->paginate(24) as $image)
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="photo-card has-link">
                      @if ($image->premium==1)
                      <span class="photo-card__badge"><i title="Premium" data-toggle="tooltip" class="las la-crown"></i></span>
                      @endif
                      <a href="{{route('image.details',[$image->id,slug($image->title)])}}" class="item-link"></a>
                    <img src="{{imageUrl($image->image_thumb)}}" alt="image" class="photo-card__thumb">
                      <div class="photo-card__content">
                        <div class="author">
                          <img src="{{getImage('assets/user/profile/'.$image->user->image)}}" alt="image">
                          <a href="javascript:void(0)" class="name">{{$image->user->username}}</a>
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
                    @lang($empty_message)
                @endforelse
             
              @endforeach
             
        </div>
    </div>  
    {{$item->paginate(24)->links()}}
</section>
@endsection