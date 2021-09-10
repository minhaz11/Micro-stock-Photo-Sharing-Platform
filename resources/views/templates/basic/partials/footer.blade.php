@php
$footer = getContent('footer.content',true);
$footerElement = getContent('footer.element',false);
$policies = getContent('policy.element',false,'',1);

@endphp



<footer class="bg--title">
  <div class="container">
      <div class="footer-top pt-80 pb-80">
          <div class="footer-logo">
            <a href="{{route('home')}}">
              <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('image')">
            </a>
          </div>
          <p>{{$footer->data_values->summery}}</p>
          <ul class="footer-links">
            @foreach ($policies as $policy)
              <li><a href="{{route('links',[slug($policy->data_values->title), $policy->id])}}">{{__($policy->data_values->title)}}</a></li>
            @endforeach
          </ul>
      </div>
      <div class="footer-bottom d-flex flex-wrap-reverse justify-content-between align-items-center py-3">
          <div class="copyright">
           @lang(' Copyright') © {{date('Y')}} | @lang('All Right Reserved by') <a href="{{url('home')}}" class="base--color">{{$general->sitename}}</a>

          </div>
          <ul class="social-icons">
              @foreach ($footerElement as $item)
                <li><a href="{{$item->data_values->link}}" target="_blank">@php echo $item->data_values->social_icon @endphp</a></li>
              @endforeach
          </ul>
      </div>
  </div>
</footer>


@push('script')

<script>
    'use strict'
    $('#subscribe').on('click',function(){
     var url = '{{route("subscribe")}}'
      var data = {
        email : $('#email').val(),
      }
      axios.post(url,data, {
      headers: {
        'Content-Type': 'application/json',
      }

      })
      .then(function (response) {
      $('#email').val('');

        if(response.data.error){
          iziToast.error({message:response.data.error, position: "topRight"})
        }

        iziToast.success({message:response.data.success, position: "topRight"})

      })
      .catch(function (error) {
        console.log(error);
      })
    })
</script>

@endpush
