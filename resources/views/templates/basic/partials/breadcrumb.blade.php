@php
$breadcrumb = getContent('breadcrumb.content',true)
@endphp
<section class="inner-hero bg_img" data-background="{{getImage('assets/images/frontend/breadcrumb/'.$breadcrumb->data_values->background_image,'1920x1080')}}">
<div class="container">
<div class="row justify-content-center">
  <div class="col-lg-6 text-center">
    <h2 class="inner-hero__title">{{trans($page_title??'')}}</h2>
  </div>
</div>
</div>
</section>