@php
    $invite = getContent('invite.content',true);
@endphp

<section class="cta-section bg_img overlay--one bg_fixed" data-background="{{getImage('assets/images/frontend/invite/'.$invite->data_values->background_image,'1920x1280')}}">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="cta-content text-center">
            <h2 class="title text-white mb-3">@lang($invite->data_values->heading)</h2>
            <p class="text-white">@lang($invite->data_values->short_description)</p>
          <a href="{{url($invite->data_values->button_link)}}" class="cmn-btn mt-5">@lang($invite->data_values->button_text)</a>
          </div>
        </div>
      </div>
    </div>
  </section>