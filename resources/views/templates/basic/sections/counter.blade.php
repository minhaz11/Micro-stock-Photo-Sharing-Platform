@php
  $content = getContent('counter.content',true);

@endphp


<div class="counter-section pt-120 pb-120 bg_img overview-wrapper bg_fixed" data-background="{{getImage ('assets/images/frontend/counter/'.$content->data_values->background_image,'1920x1280')}}">
  <div class="container">
    <div class="row mb-none-30 overview-items-wrapper">
      <div class="col-sm-6 col-lg-3 mb-30">
        <div class="overview-card">
          <div class="overview-card__icon">
            <?php echo  $content->data_values->first_count_icon ?>
          </div>
          <div class="overview-card__content">
            <span class="overview-amount">{{$content->data_values->first_count_number}}</span>
            <p class="caption">@lang($content->data_values->first_count_heading)</p>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3 mb-30">
        <div class="overview-card">
          <div class="overview-card__icon">
              <?php echo  $content->data_values->second_count_icon ?>
          </div>
          <div class="overview-card__content">
            <span class="overview-amount">{{$content->data_values->second_count_number}}</span>
            <p class="caption">@lang($content->data_values->second_count_heading)</p>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3 mb-30">
        <div class="overview-card">
          <div class="overview-card__icon">
              <?php echo  $content->data_values->third_count_icon ?>
          </div>
          <div class="overview-card__content">
            <span class="overview-amount">{{$content->data_values->third_count_number}}</span>
            <p class="caption">@lang($content->data_values->third_count_heading)</p>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3 mb-30">
        <div class="overview-card">
          <div class="overview-card__icon">
              <?php echo  $content->data_values->fourth_count_icon ?>
          </div>
          <div class="overview-card__content">
            <span class="overview-amount">{{$content->data_values->fourth_count_number}}</span>
            <p class="caption">@lang($content->data_values->fourth_count_heading)</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>