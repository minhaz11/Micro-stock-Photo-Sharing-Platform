@php
    $content = getContent('testimonial.content',true);
    $contents = getContent('testimonial.element',false);
@endphp

<section class="section--bg pt-120 pb-120 bg_img overlay--one overflow-hidden" data-background="{{getImage('assets/images/frontend/testimonial/'.$content->data_values->background_image,'1920x1280')}}">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="section-header text-center">
          <h2 class="section-title text-white">@lang($content->data_values->heading)</h2>
        </div>
      </div> 
    </div>
    <div class="testimonial-slider">
        @foreach ($contents as $item)
        <div class="single-slide">
          <div class="testimonial-img">
              <img src="{{getImage('assets/images/frontend/testimonial/'.$item->data_values->author_image, '100x100')}}" alt="@lang('author-img')">
          </div>
          <div class="testimonial-card">
            <p>@lang($item->data_values->quote)</p>
            <span class="mt-3"><b>{{$item->data_values->author}}</b> <i>{{$item->data_values->designation}}</i></span>
          </div>
        </div>
        @endforeach
    </div>
  </div>
</section>