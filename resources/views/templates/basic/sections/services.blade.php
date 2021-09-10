@php
    @$content = getContent('services.content',true)->data_values;
    $elements = getContent('services.element',false,null,1);
@endphp

<section class="pt-120 pb-120 section--bg">
      <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
              <div class="section-header text-center">
                <h2 class="section-title">{{$content->heading}}</h2>
                <p>{{$content->short_details}}</p>
              </div>
            </div>
          </div>
          <div class="row justify-content-center mb-none-30 text-center">
            @foreach ($elements as $el)  
            <div class="col-sm-6 col-xl-3 mb-30">
              <div class="choose-card">
                <div class="choose-card__icon base--color">
                    @php
                        echo $el->data_values->icon
                    @endphp
                </div>
                <div class="choose-card__content">
                  <h4 class="title mb-3">{{__($el->data_values->title)}}</h4>
                  <p>{{__($el->data_values->short_details)}}</p>
                </div>
              </div>
            </div>
            @endforeach
        </div>
      </div>
  </section>
  