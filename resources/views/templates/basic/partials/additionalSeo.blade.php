@if (Route::currentRouteName() == 'image.details')
      <title> {{ $general->sitename(__($page_title??'')) }}</title>
      <meta name="title" Content="{{ $general->sitename(__($page_title ??'')) }}">
      {{--<!-- Apple Stuff -->--}}
      <link rel="shortcut icon" href="{{ getImage(imagePath()['logoIcon']['path'] .'/favicon.png') }}" type="image/x-icon">
      <link rel="apple-touch-icon" href="{{ getImage(imagePath()['logoIcon']['path'] .'/logo.png') }}">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-status-bar-style" content="black">
      <meta name="apple-mobile-web-app-title" content="{{ $general->sitename($page_title) }}">
      {{--<!-- Google / Search Engine Tags -->--}}
      <meta itemprop="name" content="{{ $general->sitename($page_title) }}">
     
      <meta itemprop="description" content="{{$image->description}}">
      <meta itemprop="image" content="{{getImage('assets/contributor/watermark/'.$image->image_name)}}">
      {{--<!-- Facebook Meta Tags -->--}}
      <meta property="og:type" content="website">
      <meta property="og:title" content="{{  $image->title }}">
      <meta property="og:description" content="{{ $image->description }}">
      <meta property="og:image" content="{{getImage('assets/contributor/watermark/'.$image->image_name)}}"/>
      <meta property="og:image:type" content="image/{{ @pathinfo(getImage('assets/contributor/watermark/'.$image->image_name))['extension'] }}" />
      <meta property="og:url" content="{{ url()->current() }}">
      {{--<!-- Twitter Meta Tags -->--}}
      <meta name="twitter:card" content="summary_large_image">
@endif
  