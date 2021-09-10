@php
    @$content  = getContent('subscribe.content',true)->data_values;
@endphp

<section class="newsletter-section pt-120 pb-120">
    <div class="container">
        <div class="newsletter-content">
          <h2 class="newsletter-title">{{$content->heading}}</h2>
          <p>{{$content->short_details}}</p>
          <form class="subscribe-form mt-4">
            <input type="email" name="subscribe-field" id="email" placeholder="@lang('Enter email address')" class="form-control" required>
            <button type="button" class="subscribe-btn" id="subscribe"><i class="las la-arrow-right"></i></button>
          </form>
        </div>
    </div>
</section>